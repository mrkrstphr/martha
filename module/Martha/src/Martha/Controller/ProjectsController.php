<?php

namespace Martha\Controller;

use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Martha\Core\System;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class ProjectsController
 * @package Martha\Controller
 */
class ProjectsController extends AbstractActionController
{
    /**
     * @var \Martha\Core\System
     */
    protected $system;

    /**
     * @var \Martha\Core\Domain\Repository\ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @var \Martha\Core\Domain\Repository\BuildRepositoryInterface
     */
    protected $buildRepository;

    /**
     * Set us up the controller!
     *
     * @param System $system
     * @param ProjectRepositoryInterface $project
     * @param BuildRepositoryInterface $build
     */
    public function __construct(System $system, ProjectRepositoryInterface $project, BuildRepositoryInterface $build)
    {
        $this->system = $system;
        $this->projectRepository = $project;
        $this->buildRepository = $build;
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
            'pageTitle' => 'Projects',
            'projects' => $projects
        ];
    }

    /**
     * @return array
     */
    public function createAction()
    {
        return [
            'pageTitle' => 'Create Project'
        ];
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

        $builds = $this->buildRepository->getBy(['project' => $id], ['id' => 'DESC'], 15);

        return [
            'project' => $project,
            'pageTitle' => $project->getName(),
            'health' => 0.70, // todo fixme
            'builds' => $builds
        ];
    }
}
