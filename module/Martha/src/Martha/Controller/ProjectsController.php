<?php

namespace Martha\Controller;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Job\Queue;
use Zend\View\Model\JsonModel;
use Martha\Core\Domain\Entity\Project;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
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
     * Create a new CI project.
     *
     * @return array
     */
    public function createAction()
    {
        $form = $this->getServiceLocator()->get('ProjectForm')->bind(new Project());
        $providers = $this->system->getPluginManager()->getRemoteProjectProviders();
        $options = $form->get('project_type')->getValueOptions();

        foreach ($providers as $provider) {
            $options[$provider->getProviderName()] = $provider->getProviderName();
        }

        $form->get('project_type')->setValueOptions($options);

        if ($this->getRequest()->isPost()) {
            $projectType = $this->params()->fromPost('project_type');

            if ($projectType != 'generic') {
                // If a remote project was selected, grab the provider, then get information about the selected
                // project and merge it with the request
                $projectId = $this->params()->fromPost('project_id');

                if ($projectId) {
                    $provider = $this->system->getPluginManager()->getRemoteProjectProvider($projectType);
                    $projectData = $provider->getProjectInformation($projectId);

                    $form->setData(array_merge($this->request->getPost()->toArray(), $projectData));
                } else {
                    $form->setData($this->request->getPost());
                }
            } else {
                $form->setData($this->request->getPost());
            }

            if ($form->isValid()) {
                $project = $form->getData();

                if ($projectType != 'generic') {
                    $provider->onProjectCreated($projectId);
                }

                $this->projectRepository->persist($project)->flush();

                $this->redirect()->toRoute('projects/view', ['id' => $project->getId()]);
            }
        }

        return [
            'pageTitle' => 'Create Project',
            'form' => $form
        ];
    }

    /**
     * Build the specified project's most recent commit.
     *
     * @return mixed
     */
    public function buildAction()
    {
        $id = $this->params()->fromRoute('id');

        $project = $this->projectRepository->getById($id);

        if (!($project instanceof Project)) {
            $this->getResponse()->setStatusCode(404);
            return null;
        }

        $build = new Build();
        $build->setProject($project)
            ->setCreated(new \DateTime())
            ->setMethod('manual')
            ->setStatus(Build::STATUS_PENDING);

        $this->buildRepository->persist($build)->flush();

        $queue = new Queue($this->buildRepository, $this->getConfig());
        $queue->run();

        $this->redirect()->toRoute('projects/view', ['id' => $id]);
    }

    /**
     * Get a list of remote projects to create.
     *
     * @return JsonModel
     */
    public function getRemoteAction()
    {
        $provider = $this->params()->fromRoute('provider');

        $provider = $this->system->getPluginManager()->getRemoteProjectProvider($provider);

        return new JsonModel(
            [
                'projects' => $provider->getAvailableProjects()
            ]
        );
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
