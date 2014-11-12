<?php

namespace Martha\Core\Plugin\RemoteProjectProvider;

use Martha\Core\Plugin\AbstractPlugin;

/**
 * Class AbstractRemoteProjectProvider
 * @package Martha\Core\Plugin\RemoteProjectProvider
 */
abstract class AbstractRemoteProjectProvider
{
    /**
     * @var string
     */
    protected $providerName;

    /**
     * @var \Martha\Plugin\GitHub\Plugin
     */
    protected $plugin;

    /**
     * @param AbstractPlugin $plugin
     */
    public function __construct(AbstractPlugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * Get the name of the RemoteProjectProvider.
     *
     * @return string
     */
    public function getProviderName()
    {
        return $this->providerName;
    }

    /**
     * Get a list of available projects from the remote source.
     *
     * @return array
     */
    abstract public function getAvailableProjects();

    /**
     * Get information about a specific project from the remote source.
     *
     * @param $identifier
     * @return mixed
     */
    abstract public function getProjectInformation($identifier);

    /**
     * Event that is triggered when a project is created using this RemoteProjectProvider.
     * 
     * @param int $projectId
     */
    public function onProjectCreated($projectId)
    {
    }
}
