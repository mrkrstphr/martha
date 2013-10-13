<?php

namespace Martha\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class LoginController
 * @package Martha\Controller
 */
class LoginController extends AbstractActionController
{
    /**
     * Login page
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $config = $config['martha'];

        if (!isset($config['authentication']) || !isset($config['authentication']['method'])) {
            $this->getResponse()->setStatusCode(404);
            return [];
        }

        $methods = $config['authentication']['method'];
        $methods = is_array($methods) ? $methods : [$methods];

        $view = new ViewModel(
            [
                'methods' => $methods
            ]
        );

        if (in_array('github', $methods)) {
            $view->setVariable('clientId', $config['authentication']['github_client_id']);
        }

        return $view;
    }

    /**
     * OAuth Callback after authentication.
     */
    public function oauthCallbackAction()
    {
        $code = $this->params()->fromQuery('code');

        // todo fixme finishme
    }
}
