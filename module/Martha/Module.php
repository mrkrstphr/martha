<?php

namespace Martha;

use Martha\Core\System;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\Literal;
use Zend\Stdlib\ResponseInterface;

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

        $eventManager->attach(MvcEvent::EVENT_RENDER, [$this, 'onRender']);
        $eventManager->attach(MvcEvent::EVENT_ROUTE, [$this, 'onRoute']);

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
     * EVENT_ROUTE listener for handling user login authentication.
     *
     * @param MvcEvent $e
     * @return ResponseInterface|null
     */
    public function onRoute(MvcEvent $e)
    {
        $config = $e->getApplication()->getServiceManager()->get('Config');
        $config = $config['martha'];

        if (!isset($config['authentication']) ||
            (isset($config['authentication']['mode']) && $config['authentication']['mode'] == 'lenient')
        ) {
            return null;
        }

        $routeMatch = $e->getRouteMatch();
        $response = $e->getResponse();
        $login = new AuthenticationService();

        if (!$login->hasIdentity() && !in_array($routeMatch->getMatchedRouteName(), ['login', 'logout', 'register'])) {
            $router = $e->getRouter();
            $response->setStatusCode(302);
            $response->getHeaders()->addHeaderLine('Location', $router->assemble([], ['name' => 'login']));

            return $response;
        }
    }

    /**
     * @param MvcEvent $e
     */
    public function onRender(MvcEvent $e)
    {
        $model = current($e->getViewModel()->getChildren());

        // If a page title was given to the view, prepend it to the helper:
        if ($model && $model->getVariable('pageTitle')) {
            $viewHelperManager = $e->getApplication()->getServiceManager()->get('viewHelperManager');
            $headTitleHelper   = $viewHelperManager->get('headTitle');
            $headTitleHelper->prepend($model->getVariable('pageTitle'));
        }

        $login = new AuthenticationService();
        if ($login->hasIdentity()) {
            $model->setVariable('identity', $login->getIdentity());
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
