<?php

namespace Martha\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ModelInterface;

/**
 * Class AbstractMarthaController
 * @package Martha\Controller
 */
class AbstractMarthaController extends AbstractActionController
{
    /**
     * @var ModelInterface
     */
    protected $view;

    /**
     * @param string $key
     * @return array
     */
    public function getConfig($key = null)
    {
        $config = $this->serviceLocator->get('Config');

        if ($key) {
            if (!isset($config[$key])) {
                return false;
            }

            $config = $config[$key];
        }

        return $config;
    }
}
