<?php

namespace Martha\Controller;

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
     * @throws \Exception
     * @return array
     */
    public function hookAction()
    {
        $notify = file_get_contents(__DIR__ . '/sample-hook.js');
        $notify = str_replace(['var commit = ', '};'], ['', '}'], $notify);

        $notify = json_decode($notify, true);

        $config = $this->getServiceLocator()->get('Config');
        $config = isset($config['martha']) ? $config['martha'] : false;

        if (!$config) {
            throw new \Exception('Please create your system.local.php configuration file');
        }

        $hook = GithubWebHookFactory::createHook($notify);
        $runner = new Runner($hook, $config);
        $runner->run();

        return new JsonModel();
    }

    public function viewAction()
    {
        $id = $this->params('id');



        return [
            'pageTitle' => 'Build #' . $id
        ];
    }
}
