<?php

namespace Martha\Plugin\GitHub\WebHook;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;

/**
 * Class Factory
 * @package Martha\Plugin\GitHub\WebHook
 */
class BuildFactory
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @param array $payload
     * @return Build
     * @throws PayloadException
     */
    public function createBuildFromPullRequest(array $payload)
    {
        $repositoryData = isset($payload['repository']) ? $payload['repository'] : false;
        if (!$repositoryData) {
            throw new PayloadException('Repository data is missing from the payload');
        }

        $projectName = isset($repositoryData['full_name']) ? $repositoryData['full_name'] : '';
        $project = $this->projectRepository->getBy(['name' => $projectName]);
        if (!count($project)) {
            throw new PayloadException('Project not found: ' . $projectName);
        }

        $build = new Build();
        $build->setProject($project[0]);
        $build->setMethod('GitHub:WebHook');

        $build->setRevisionNumber($payload['pull_request']['head']['sha']);
        $build->setBranch(basename($payload['pull_request']['head']['ref']));

        if ($payload['pull_request']['head']['repo']['full_name'] != $build->getProject()->getName()) {
            $build->setFork($payload['pull_request']['head']['repo']['ssh_url']);
            $build->setForkUri($build->getFork()); // wtf?
        }

        $build->setStatus(Build::STATUS_PENDING);
        $build->setCreated(new \DateTime());
        $build->getMetadata()
            ->set('triggered-by', 'GitHubWebHook')
            ->set('pull-request', $payload['number']);

        return $build;
    }
}
