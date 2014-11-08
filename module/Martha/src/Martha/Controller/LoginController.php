<?php

namespace Martha\Controller;

use Martha\Core\Authentication\AuthenticationService;
use Symfony\Component\HttpFoundation\Request;
use Zend\Authentication;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class LoginController
 * @package Martha\Controller
 */
class LoginController extends AbstractActionController
{
    /**
     * @var AuthenticationService
     */
    protected $authService;

    /**
     * @param AuthenticationService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Login page
     *
     * @return array
     */
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $config = $config['martha'];

        if (!isset($config['authentication']) || !isset($config['authentication']['enabled']) ||
            !$config['authentication']['enabled']
        ) {
            $this->getResponse()->setStatusCode(404);
            return [];
        }

        return [
            'oauth' => $this->authService->getRegisteredoAuthProviders()
        ];
    }

    /**
     * @return \Zend\Http\Response
     */
    public function oauthAction()
    {
        $provider = $this->authService->getAuthenticationProvider(
            $this->params()->fromQuery('service')
        );

        if ($provider) {
            $provider->prepareForRedirect();
            return $this->redirect()->toUrl($provider->getUrl());
        }

        return $this->redirect()->toRoute('login');
    }

    /**
     * OAuth Callback after authentication.
     * @return \Zend\Http\Response
     */
    public function oauthCallbackAction()
    {
        $service = $this->params()->fromRoute('id');
        $request = Request::createFromGlobals();

        if (($user = $this->authService->authenticateWithOAuthProvider($service, $request)) !== false) {
            $auth = new Authentication\AuthenticationService();
            $auth->getStorage()->write($user);
            return $this->redirect()->toUrl('/');
        }

        // some kind of flash message
        return $this->redirect()->toUrl('/login');
    }

    /**
     * Provides the ability to destroy an authenticated session.
     */
    public function logoutAction()
    {
        $auth = new Authentication\AuthenticationService();
        $auth->clearIdentity();

        $this->redirect()->toUrl('/');
    }
}
