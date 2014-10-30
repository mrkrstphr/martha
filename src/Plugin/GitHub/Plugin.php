<?php

namespace Martha\Plugin\GitHub;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Martha\Core\Http\Request;
use Martha\Core\Job\Queue;
use Martha\Core\Plugin\AbstractPlugin;
use Martha\Core\Plugin\ArtifactHandlers\TextBasedResultInterface;
use Martha\GitHub\Client;
use Martha\Plugin\GitHub\WebHook\Factory;

/**
 * Class Plugin
 * @package Martha\Plugin\GitHub
 */
class Plugin extends AbstractPlugin
{
    /**
     * @var BuildRepositoryInterface
     */
    protected $buildRepository;

    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @var \Martha\GitHub\Client
     */
    protected $apiClient;

    /**
     * Configure and register the plugin.
     *
     * @throws \Exception
     */
    public function init()
    {
        if (!isset($this->config['access_token'])) {
            throw new \Exception('No GitHub access token provided');
        }

        $factory = $this->getPluginManager()->getSystem()->getRepositoryFactory();

        $this->buildRepository = $factory->createBuildRepository();
        $this->projectRepository = $factory->createProjectRepository();

        $this->getPluginManager()->registerRemoteProjectProvider(
            $this,
            '\Martha\Plugin\GitHub\RemoteProjectProvider'
        );

        $this->getPluginManager()->registerHttpRouteHandler(
            'github-web-hook',
            '/build/github-web-hook',
            [$this, 'onWebHook']
        );

        $this->getPluginManager()->getEventManager()
            ->registerListener(
                'build.started',
                [$this, 'onBuildStart']
            )
            ->registerListener(
                'build.complete',
                [$this, 'onBuildComplete']
            );

        $this->getPluginManager()->registerAuthenticationProvider(
            $this,
            '\Martha\Plugin\GitHub\Authentication\Provider\GitHubAuthProvider'
        );
    }

    /**
     * @param Request $request
     * @return array
     */
    public function onWebHook(Request $request)
    {
        $payload = '';

        if ($request->getBody()) {
            $payload = $request->getBody();
        } else if ($request->getPost('payload')) {
            $payload = $request->getPost('payload');
        }

        if (!$payload || !($payload = json_decode($payload, true))) {
            $message = 'Invalid GitHub Payload';
            $this->getPluginManager()->getLogger()->error($message, ['data' => $payload]);
            return ['success' => false, 'description' => $message];
        }

        if (!in_array($payload['action'], ['opened', 'reopened', 'synchronize'])) {
            $message = 'Unhandled GitHub Payload: ' . $payload['action'];
            $this->getPluginManager()->getLogger()->error($message, ['data' => $payload]);
            return ['success' => false, 'description' => $message];
        }

        $hook = Factory::createHook($payload);

        $project = $this->projectRepository->getBy(['name' => $hook->getRepository()->getName()]);

        if (!$project) {
            $message = 'Project not found: ' . $hook->getRepository()->getName();
            $this->getPluginManager()->getLogger()->error($message, ['data' => $payload]);
            return ['status' => 'failed', 'description' => $message];
        }

        $project = current($project);

        $build = new Build();
        $build->setMethod('GitHub Web Hook');
        $build->setProject($project);
        $build->setBranch($hook->getBranch());
        $build->setFork($hook->getFork());
        $build->setRevisionNumber($hook->getRevisionNumber());
        $build->setForkUri($hook->getFork());
        $build->setStatus(Build::STATUS_PENDING);
        $build->setCreated(new \DateTime());
        $build->getMetadata()
            ->set('triggered-by', 'GitHubWebHook')
            ->set('pull-request', $payload['number']);

        $this->buildRepository->persist($build)->flush();

        // Force the Build Queue to be checked now, instead of waiting for a scheduled run:

        $queue = new Queue($this->buildRepository, $this->getConfig());
        $queue->run();

        return [];
    }

    /**
     * On "build-complete", update GitHub with the status of the build by adding a status to the last commit in the
     * build. Also, if enabled via the "add_build_summary" option, provide a summary of the build as a comment on
     * the GitHub Pull Request.
     *
     * @param string $event
     * @param Build $build
     */
    public function onBuildStart($event, Build $build)
    {
        $project = $build->getProject();

        if ($build->getMetadata()->get('triggered-by') == 'GitHubWebHook') {
            list($owner, $repo) = explode('/', $project->getName());

            $response = $this->getApi()->repositories()->statuses()->create(
                $owner,
                $repo,
                $build->getRevisionNumber(),
                [
                    'state' => 'pending',
                    'description' => 'The Martha CI Build is pending',
                    'target_url' => $this->getPluginManager()->getSystem()->getSiteUrl() .
                        '/build/view/' . $build->getId()
                ]
            );
        }
    }

    /**
     * @param string $event
     * @param Build $build
     */
    public function onBuildComplete($event, Build $build)
    {
        $project = $build->getProject();

        if ($build->getMetadata()->get('triggered-by') == 'GitHubWebHook') {
            list($owner, $repo) = explode('/', $project->getName());

            $this->getApi()->repositories()->statuses()->create(
                $owner,
                $repo,
                $build->getRevisionNumber(),
                [
                    'state' => $build->getStatus() == Build::STATUS_SUCCESS ? 'success' : 'failure',
                    'description' => 'The Martha CI Build ' .
                        ($build->getStatus() == Build::STATUS_SUCCESS ? 'Passed' : 'Failed'),
                    'target_url' => $this->getPluginManager()->getSystem()->getSiteUrl() .
                        '/build/view/' . $build->getId()
                ]
            );

            if (isset($this->config['add_build_summary']) && $this->config['add_build_summary']) {
                if ($build->getMetadata()->has('pull-request')) {
                    // If this build has an associated Pull Request, add a comment to the Pull Request with build info:
                    $number = $build->getMetadata()->get('pull-request');
                    $this->commentOnPullRequest($owner, $repo, $number, $build);
                }
            }
        }
    }

    /**
     * Comment on the Pull Request associated with this build. Include general details about the build, including:
     *  1. Build date
     *  2. Link to the build
     *  3. Success or failure
     *
     * In addition to this, loop all the artifacts, and if the handler is an instance of TextBasedResultInterface,
     * retrieve the text-based result and add it to the comment.
     *
     * @param string $owner
     * @param string $repo
     * @param int $number
     * @param Build $build
     */
    protected function commentOnPullRequest($owner, $repo, $number, Build $build)
    {
        // Create a comment with our generic build information:
        $comment = '**[Martha Build #' . $build->getId() . ']' .
            '(' . $this->getPluginManager()->getSystem()->getSiteUrl() . '/build/view/' .
            $build->getId() . ')** completed **' . date('r') . "**\n\n" .
            'Status: **' . ucwords($build->getStatus()) . "**";

        // Loop each artifact generated by the build:
        foreach ($build->getArtifacts() as $artifact) {
            // Get the handler for the artifact:
            $artifactHandler = $this->getPluginManager()->getArtifactHandler($artifact->getHelper());

            // If we're an instance of TextBasedResultInterface, grab the text-based result
            // and append it to the comment:
            if ($artifactHandler && $artifactHandler instanceof TextBasedResultInterface) {
                $comment .= "\n\n## " . $artifact->getHelper() . "\n";
                $artifactHandler->parseArtifact($build, file_get_contents($artifact->getFile()));
                $comment .= $artifactHandler->getSimpleTextResult();
            }
        }

        $this->getApi()->repositories()->issues()->comments()->create(
            $owner,
            $repo,
            $number,
            ['body' => $comment]
        );
    }

    /**
     * Gets an instance of a configured GitHub API client and returns it.
     *
     * @return Client
     */
    protected function getApi()
    {
        if ($this->apiClient) {
            return $this->apiClient;
        }

        $config = $this->getConfig();
        $this->apiClient = new Client($config);

        return $this->apiClient;
    }
}
