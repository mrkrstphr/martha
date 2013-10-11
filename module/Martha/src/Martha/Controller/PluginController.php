<?php

namespace Martha\Controller;

use Martha\Core\Http\Request;
use Martha\Core\System;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class PluginController
 * @package Martha\Controller
 */
class PluginController extends AbstractActionController
{
    /**
     * @var System
     */
    protected $system;

    /**
     * Set us up the controller!
     *
     * @param System $system
     */
    public function __construct(System $system)
    {
        $this->system = $system;
    }

    /**
     * Handle a plugin generated route by calling its callback.
     *
     * @return JsonModel
     */
    public function customRouteAction()
    {
        $uri = str_replace(
            '?' . $this->getRequest()->getServer()->get('QUERY_STRING', ''),
            '',
            $this->getRequest()->getRequestUri()
        );

        $route = $this->system->getPluginManager()->getHttpRoute($uri);

        if (!$route) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }

        if (is_callable($route['callback'])) {
            $request = (new Request())
                ->setBody($this->getRequest()->getContent())
                ->setGet($this->params()->fromQuery())
                ->setPost($this->params()->fromPost());

            $data = $route['callback']($request);
        } else {
            $this->getResponse()->setStatusCode(404);
            return null;
        }

        return new JsonModel(is_array($data) ? $data : array());
    }
}
