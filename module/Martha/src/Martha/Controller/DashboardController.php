<?php

namespace Martha\Controller;

use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Zend\View\Model\ViewModel;

/**
 * Class DashboardController
 * @package Martha\Controller
 */
class DashboardController extends AbstractMarthaController
{
    /**
     * @param ProjectRepositoryInterface $projectRepository
     */
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @return array
     */
    public function indexAction()
    {
        $this->view = new ViewModel();
        $this->getProjects();

        return $this->view;
    }
}
