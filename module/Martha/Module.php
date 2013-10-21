<?php

namespace Martha;

use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\Literal;
use Martha\Core\System;

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
     * @todo clean this crap up. too much code in here
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $this->application = $e->getApplication();

        $eventManager = $this->application->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $eventManager->attach('render', array($this, 'onRender'));

        $config = $this->application->getServiceManager()->get('Config');

        System::initialize(
            $this->application->getServiceManager()->get('Doctrine\ORM\EntityManager'),
            $config['martha']
        );

        $system = System::getInstance();
        $routes = $system->getPluginManager()->getHttpRoutes();
        $router = $this->application->getServiceManager()->get('Router');

        // ZF2 routing sucks when an app is both CLI and HTTP
        $reflector = new \ReflectionClass($router);

        if ($reflector->getNamespaceName() != 'Zend\Mvc\Router\Console') {
            foreach ($routes as $route) {
                $newRoute = Literal::factory(
                    [
                        'route' => $route['route'],
                        'defaults' => [
                            'controller' => 'Martha\Controller\Plugin',
                            'action' => 'custom-route'
                        ]
                    ]
                );

                $router->addRoute($route['name'], $newRoute);
            }
        }
    }

    /**
     * @param MvcEvent $e
     */
    public function onRender(MvcEvent $e)
    {
        // Add view paths for individual plugins:
        $vm = $this->application->getServiceManager()->get('ViewTemplatePathStack');

        foreach (System::getInstance()->getPluginManager()->getRegisteredViewPaths() as $pluginPaths) {
            foreach ($pluginPaths as $path) {
                $vm->addPath($path);
            }
        }

        $model = current($e->getViewModel()->getChildren());

        // If a page title was given to the view, prepend it to the helper:
        if ($model && $model->getVariable('pageTitle')) {
            $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');
            $headTitleHelper   = $viewHelperManager->get('headTitle');
            $headTitleHelper->prepend($model->getVariable('pageTitle'));
        }
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
     * Get the configured View Helpers.
     *
     * @return array
     */
    public function getViewHelperConfig()
    {
        return include __DIR__ . '/config/view-helper.config.php';
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
