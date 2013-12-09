<?php

namespace Martha\Controller;

use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class DashboardController
 * @package Martha\Controller
 */
class DashboardController extends AbstractActionController
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @param ProjectRepositoryInterface $project
     * @param BuildRepositoryInterface $build
     */
    public function __construct(ProjectRepositoryInterface $project, BuildRepositoryInterface $build)
    {
        $this->projectRepository = $project;
        $this->buildRepository = $build;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $builds = $this->buildRepository->getBy([], ['created' => 'DESC'], 10);

        return [
            'js' => ['/js/dashboard.js'],
            'builds' => $builds
        ];
    }
}
