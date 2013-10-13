<?php

namespace Martha\Controller;

use Martha\Authentication\Adapter\GitHubAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class LoginController
 * @package Martha\Controller
 */
class LoginController extends AbstractActionController
{
    /**
     * Login page
     *
     * @return array
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

        $data = ['methods' => $methods];

        if (in_array('github', $methods)) {
            $data['clientId'] = $config['authentication']['github_client_id'];
            $data['scope'] = 'user,repo';
        }

        return $data;
    }

    /**
     * OAuth Callback after authentication.
     */
    public function oauthCallbackAction()
    {
        $code = $this->params()->fromQuery('code');

        $config = $this->getServiceLocator()->get('Config');
        $config = $config['martha'];

        $adapter = new GitHubAdapter($config['authentication']);
        $adapter->setCredential($code);

        $auth = new AuthenticationService();
        $result = $auth->authenticate($adapter);

        if ($result->isValid()) {
            $this->redirect()->toUrl('/');
        } else {
            $this->redirect()->toUrl('/login');
        }
    }

    /**
     * Provides the ability to destroy an authenticated session.
     */
    public function logoutAction()
    {
        $auth = new AuthenticationService();
        $auth->clearIdentity();

        $this->redirect()->toUrl('/');
    }
}
