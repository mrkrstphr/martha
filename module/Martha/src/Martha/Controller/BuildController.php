<?php

namespace Martha\Controller;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Martha\Core\Job\Queue;
use Martha\Core\Plugin\ArtifactHandlers\DashboardWidgetProviderInterface;
use Martha\Core\Plugin\ArtifactHandlers\TabProviderInterface;
use Martha\View\Model\MarthaViewAdapter;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class BuildController
 * @package Martha\Controller
 */
class BuildController extends AbstractActionController
{
    /**
     * @var ProjectRepositoryInterface
     */
    protected $projectRepository;

    /**
     * @var BuildRepositoryInterface
     */
    protected $buildRepository;

    /**
     * Set us up the controller!
     *
     * @param ProjectRepositoryInterface $projectRepo
     * @param BuildRepositoryInterface $buildRepo
     */
    public function __construct(ProjectRepositoryInterface $projectRepo, BuildRepositoryInterface $buildRepo)
    {
        $this->projectRepository = $projectRepo;
        $this->buildRepository = $buildRepo;
    }

    /**
     * @return ViewModel
     */
    public function viewAction()
    {
        $id = $this->params()->fromRoute('id');
        $build = $this->buildRepository->getById($id);

        $config = $this->getConfig();

        $outputDir = $config['data-directory'] . '/' . $build->getProject()->getName() . '/' . $id;
        $output = file_exists($outputDir . '/console.html') ?
            file_get_contents($outputDir . '/console.html') : 'None Available';

        $viewModel = new ViewModel(
            [
                'pageTitle' => 'Build #' . $id,
                'output' => $output,
                'build' => $build
            ]
        );

        $artifacts = [
            'dashboard' => [],
            'tabbed' => []
        ];

        foreach ($build->getArtifacts() as $artifact) {
            $artifactHandler = $this->getServiceLocator()->get('System')
                ->getPluginManager()->getArtifactHandler($artifact->getHelper());

            if ($artifactHandler && $artifactHandler instanceof DashboardWidgetProviderInterface) {
                $artifactHandler->parseArtifact($build, file_get_contents($artifact->getFile()));
                $view = new MarthaViewAdapter($artifactHandler->getDashboardWidget());
                $viewModel->addChild($view, $artifact->getHelper() . '-widget');

                $artifacts['dashboard'][$artifact->getHelper() . '-widget'] = $view->getVariable('widgetTitle');
            }

            if ($artifactHandler && $artifactHandler instanceof TabProviderInterface) {
                $artifactHandler->parseArtifact($build, file_get_contents($artifact->getFile()));
                $view = new MarthaViewAdapter($artifactHandler->getTabbedPane());
                $viewModel->addChild($view, $artifact->getHelper() . '-tab');

                $artifacts['tabbed'][$artifact->getHelper() . '-tab'] = $view->getVariable('tabTitle');
            }
        }

        $viewModel->setVariable('artifacts', $artifacts);

        return $viewModel;
    }

    /**
     * @throws \Exception
     * @return array
     */
    protected function getConfig()
    {
        $config = $this->getServiceLocator()->get('Config');
        $config = isset($config['martha']) ? $config['martha'] : false;

        if (!$config) {
            throw new \Exception('Please create your system.local.php configuration file');
        }

        return $config;
    }
}
