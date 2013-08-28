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

        $sharedEvents = $eventManager->getSharedManager();
        $sharedEvents->attach('Zend\View\View', ViewEvent::EVENT_RENDERER, [$this, 'onRender']);

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * Ensure that the view always has a list of projects.
     *
     * @param ViewEvent $event
     */
    public function onRender(ViewEvent $event)
    {
        $event->getModel()->projects = [];
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
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
