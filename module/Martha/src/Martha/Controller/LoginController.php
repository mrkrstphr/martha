<?php

namespace Martha\Controller;


use Martha\Core\Authentication\Provider\AbstractOAuthProvider;
use Martha\Core\Domain\Repository\UserRepositoryInterface;
use Martha\Core\Http\Request;
use Zend\Authentication\AuthenticationService;
use Zend\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class LoginController
 * @package Martha\Controller
 */
class LoginController extends AbstractActionController
{
    /**
     * @var \Martha\Core\Domain\Repository\UserRepositoryInterface
     */
    protected $repository;

    /**
     * @param \Martha\Core\Domain\Repository\UserRepositoryInterface $repository
     */
    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Login page
     *
     * @return array
     */
    public function indexAction()
    {
        $system = $this->getServiceLocator()->get('System');
        $config = $this->getServiceLocator()->get('Config');
        $config = $config['martha'];

        if (!isset($config['authentication']) || !isset($config['authentication']['enabled']) ||
            !$config['authentication']['enabled']
        ) {
            $this->getResponse()->setStatusCode(404);
            return [];
        }

        $data = [
            'oauth' => []
        ];

        foreach ($system->getPluginManager()->getAuthenticationProviders() as $provider) {
            if ($provider instanceof AbstractOAuthProvider) {
                $data['oauth'][] = $provider;
            }
        }

        return $data;
    }

    /**
     * OAuth Callback after authentication.
     */
    public function oauthCallbackAction()
    {
        $request = (new Request())
            ->setBody($this->getRequest()->getContent())
            ->setGet($this->params()->fromQuery())
            ->setPost($this->params()->fromPost());

        $system = $this->getServiceLocator()->get('System');

        foreach ($system->getPluginManager()->getAuthenticationProviders() as $provider) {
            if ($provider instanceof AbstractOAuthProvider) {
                if (($user = $provider->validateResult($request))) {
                    $dbUser = $this->repository->getBy(['email' => $user->getEmail()]);

                    if (!$dbUser) {
                        $this->repository->persist($user)->flush();
                        $dbUser = $user;
                    } else {
                        $dbUser = current($dbUser);
                    }

                    $auth = new AuthenticationService();
                    $auth->getStorage()->write($dbUser);
                    $this->redirect()->toUrl('/');
                    return;
                }
            }
        }

        $this->redirect()->toUrl('/login');
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
