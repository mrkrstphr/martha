<?php

namespace Martha\Controller;

use Zend\View\Model\ViewModel;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Martha\Form\Project\CreateGenericScmProject;
use Martha\Form\Project\CreateGitHubProject;
use Martha\Core\System;

/**
 * Class ProjectsController
 * @package Martha\Controller
 */
class ProjectsController extends AbstractMarthaController
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
        $config = $this->getConfig('martha');

        $options = [$this->url()->fromRoute('projects/create/scm') => 'Generic SCM Project'];

        if (isset($config['github_access_token']) && !empty($config['github_access_token'])) {
            $options[$this->url()->fromRoute('projects/create/github')] = 'GitHub Project';
        }

        return [
            'pageTitle' => 'Create Project',
            'options' => $options
        ];
    }

    /**
     * @return array
     */
    public function createScmProjectAction()
    {
        $form = new CreateGenericScmProject();

        return [
            'form' => $form
        ];
    }

    /**
     * @param array $config
     * @return ViewModel
     */
    protected function createGitHubProject(array $config)
    {
        $gitHub = new GitHub($config['github_access_token']);
        $projects = $gitHub->getProjects();

        $form = (new CreateGitHubProject())
            ->setProjects($projects);

        if ($this->getRequest()->isPost()) {
            $form->setData($this->request->getPost());

            if ($form->isValid($this->params())) {
                $data = $form->getData();

                $projectFactory = new ProjectFactory();
                $project = $projectFactory->createFromGitHub($gitHub, $data['project_id']);

                $this->projectRepository->persist($project)->flush();

                $this->redirect('view-project', ['id' => $project->getId()]);
            }
        }

        $gitHub = new ViewModel(
            [
                'form' => $form,
                'projects' => $projects
            ]
        );

        $gitHub->setTemplate('martha/projects/create-project-github.phtml');

        return $gitHub;
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
