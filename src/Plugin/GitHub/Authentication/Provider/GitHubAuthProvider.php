<?php

namespace Martha\Plugin\GitHub\Authentication\Provider;

use Martha\Core\Authentication\Provider\AbstractOAuthProvider;
use Martha\Core\Domain\Entity\User;
use Martha\Core\Http\Request;
use Martha\GitHub\Client;

/**
 * Class GitHubAuthProvider
 * @package Martha\Plugin\GitHub
 */
class GitHubAuthProvider extends AbstractOAuthProvider
{
    /**
     * @var string
     */
    protected $name = 'GitHub';

    /**
     * @var string
     */
    protected $baseUrl = 'https://github.com/login/oauth/authorize?client_id=';

    /**
     * @var string
     */
    protected $icon = '/images/github-icon.png';

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->baseUrl .= $this->config['client_id'] . '&scope=user,repo';
    }

    /**
     *
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param Request $request
     * @return boolean|\Martha\Core\Domain\Entity\User
     */
    public function validateResult(Request $request)
    {
        if (!$request->getGet('code')) {
            return false;
        }

        $code = $request->getGet('code');
        $config = $this->getConfig();

        $params = [
            'code' => $code,
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret']
        ];

        $github = new Client();
        $result = $github->login()->oauth()->accessToken($params);

        if (isset($result['error']) || !isset($result['access_token'])) {
            return false;
        }

        $github = new Client(['access_token' => $result['access_token']]);
        $userInfo = $github->me()->user();

        $emails = $github->me()->emails()->emails();

        $user = new User();
        $user->setFullName($userInfo['name']);
        $user->setAuthService('GitHub');
        $user->setAccessToken($result['access_token']);

        foreach ($emails as $email) {
            if ($email['primary']) {
                $user->setEmail($email['email']);
                break;
            }
        }

        return $user;
    }
}
