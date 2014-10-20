<?php

namespace Martha\Plugin\GitHub;

use Martha\Core\Plugin\RemoteProjectProvider\AbstractRemoteProjectProvider;
use Martha\GitHub\Client;

/**
 * Class RemoteProjectProvider
 * @package Martha\Plugin\GitHub
 */
class RemoteProjectProvider extends AbstractRemoteProjectProvider
{
    /**
     * The name of this Remote Project Provider.
     *
     * @var string
     */
    protected $providerName = 'GitHub';

    /**
     * Get all available projects for the authenticated account, including organizations.
     *
     * @return array
     */
    public function getAvailableProjects()
    {
        $api = $this->getApi();
        // Get all repositories:
        $repositories = $api->me()->repositories();

        // Get all organizations:
        $orgs = $api->me()->organizations();

        foreach ($orgs as $org) {
            // Merge the organization repositories with the user repositories:
            $orgRepos = $api->organizations()->repositories()->repositories($org['login']);
            $repositories = array_merge($repositories, $orgRepos);
        }

        $projects = [];

        foreach ($repositories as $repository) {
            $projects[$repository['full_name']] = $repository['full_name'];
        }

        ksort($projects);

        return $projects;
    }

    /**
     * Get information about a GitHub project. $identifier must be in the format of "owner/repo"
     *
     * @param string $identifier
     * @return array
     */
    public function getProjectInformation($identifier)
    {
        list($owner, $repo) = explode('/', $identifier);
        $project = $this->getApi()->repositories()->repository($owner, $repo);

        return [
            'name' => $identifier,
            'description' => $project['description'],
            'scm' => 'git',
            'uri' => $project['clone_url']
        ];
    }

    /**
     * @param int $projectId
     */
    public function onProjectCreated($projectId)
    {
        list($owner, $repo) = explode('/', $projectId);

        $this->getApi()->repositories()->hooks()->create(
            $owner,
            $repo,
            [
                'name' => 'web',
                'active' => true,
                'events' => [
                    'pull_request'
                ],
                'config' => [
                    'url' => $this->plugin->getPluginManager()->getSystem()->getSiteUrl() . '/build/github-web-hook',
                    'content_type' => 'json'
                ]
            ]
        );
    }

    /**
     * @return Client
     */
    protected function getApi()
    {
        $config = $this->plugin->getConfig();
        $api = new Client($config);

        return $api;
    }
}
