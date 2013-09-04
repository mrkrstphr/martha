<?php

namespace Martha\Controller;

use Martha\Core\Domain\Entity\Build;
use Martha\Core\Domain\Repository\BuildRepositoryInterface;
use Martha\Core\Domain\Repository\ProjectRepositoryInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Martha\Core\Job\Runner;
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
     * @throws \Exception
     * @return JsonModel
     */
    public function hookAction()
    {
        // mock the web hook:
        $notify = file_get_contents(__DIR__ . '/sample-hook.js');
        $notify = str_replace(['var commit = ', '};'], ['', '}'], $notify);
        $notify = json_decode($notify, true);

        $config = $this->getConfig();

        $hook = GithubWebHookFactory::createHook($notify);

        $project = $this->projectRepository->getBy(['name' => $hook->getFullProjectName()]);

        if (!$project) {
            return new JsonModel(['status' => 'failed', 'description' => 'Project not found']);
        }

        $project = current($project);

        $build = new Build();
        $build->setProject($project);
        $build->setBranch($hook->getBranch());
        $build->setFork($hook->getFork());
        $build->setRevisionNumber($hook->getRevisionNumber());
        $build->setStatus(Build::STATUS_BUILDING);
        $build->setCreated(new \DateTime());

        $this->buildRepository->persist($build)->flush();

        $runner = new Runner($hook, $build, $config);
        $wasSuccessful = $runner->run();

        $build->setStatus($wasSuccessful ? Build::STATUS_SUCCESS : Build::STATUS_FAILURE);
        $this->buildRepository->flush();

        return new JsonModel();
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
