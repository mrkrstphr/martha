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
     * Set us up the controller!
     *
     * @param ProjectRepositoryInterface $projectRepo
     */
    public function __construct(ProjectRepositoryInterface $projectRepo)
    {
        $this->projectRepository = $projectRepo;
    }

    /**
     * Get all projects for display.
     *
     * @return array
     */
    public function indexAction()
    {
        $projects = $this->projectRepository->getBy([], ['name' => 'ASC']);

        return [
            'projects' => $projects
        ];
    }

    /**
     * @return array
     */
    public function createAction()
    {
        return [];
    }

    /**
     * View the project.
     *
     * @return array
     */
    public function viewAction()
    {
        $id = $this->params('id');

        $project = $this->projectRepository->getById($id);

        if (!$project) {
            $this->getResponse()->setStatusCode(404);
            return [];
        }

        return [
            'project' => $project,
            'health' => 0.70, // todo fixme
            'builds' => []
        ];
    }
}
