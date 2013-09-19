<?php

namespace Martha\Controller;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Martha\Core\Job\Queue;
use Zend\Mvc\Controller\AbstractActionController;
use Martha\Core\Job\Trigger\GitHubWebHook\Factory as GithubWebHookFactory;
use Zend\View\Model\JsonModel;

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
     * @return array
     */
    public function viewAction()
    {
        $id = $this->params('id');

        $build = $this->buildRepository->getById($id);

        $config = $this->getConfig();

        $outputDir = $config['data-directory'] . '/' . $build->getProject()->getName() . '/' . $id;

        $output = file_exists($outputDir . '/console.html') ?
            file_get_contents($outputDir . '/console.html') : 'None Available';


        return [
            'pageTitle' => 'Build #' . $id,
            'output' => $output,
            'build' => $build
        ];
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