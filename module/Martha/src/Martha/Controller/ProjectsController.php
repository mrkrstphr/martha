<?php

namespace Martha\Controller;

use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class ProjectsController
 * @package Martha\Controller
 */
class ProjectsController extends AbstractActionController
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @param ProjectRepositoryInterface $projectRepo
     */
    public function __construct(ProjectRepositoryInterface $projectRepo)
    {
        $this->projectRepository = $projectRepo;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @return array
     */
    public function createAction()
    {
        return [];
    }

    /**
     * @return array
     */
    public function viewAction()
    {
        return [];
    }
}
