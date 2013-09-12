<?php

namespace Martha\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ModelInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;

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
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     *
     */
    protected function getProjects()
    {
        $this->view->projects = $this->projectRepository->getAll();
    }

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
