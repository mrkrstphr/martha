<?php

namespace Martha;

use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\ViewEvent;

/**
 * Class Module
 * @package Martha
 */
class Module
{
    /**
     * @var \Zend\Mvc\Application
     */
    protected $application;

    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $this->application = $e->getApplication();

        $eventManager = $this->application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * Get the service configuration.
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return include __DIR__ . '/config/service.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
