<?php
/*
 * Copyright (C) 2013 Kristopher Wilson <kristopherwilson@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

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
