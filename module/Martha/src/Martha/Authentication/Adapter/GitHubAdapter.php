<?php

namespace Martha\Authentication\Adapter;

use Martha\Core\Domain\Entity\User;
use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Zend\Http\Client;
use Zend\Http\Request;

/**
 * Class GitHubAdapter
 * @package Martha\Authentication\Adapter
 */
class GitHubAdapter extends AbstractAdapter
{
    /**
     * GitHub Application Client ID
     * @var string
     */
    protected $clientId;

    /**
     * GitHub Application Client Secret
     * @var string
     */
    protected $clientSecret;

    /**
     * Set up the adapter.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['github_client_id'])) {
            $this->clientId = $config['github_client_id'];
        }

        if (isset($config['github_client_secret'])) {
            $this->clientSecret = $config['github_client_secret'];
        }
    }

    /**
     * Attempt to authenticate the GitHub OAuth code, return the result.
     *
     * @return Result
     */
    public function authenticate()
    {
        $code = $this->getCredential();

        $request = new Request();
        $request->setUri('https://github.com/login/oauth/access_token');
        $request->setMethod('POST');
        $request->getPost()
            ->set('client_id', $this->clientId)
            ->set('client_secret', $this->clientSecret)
            ->set('code', $code);

        $client = new Client();
        $client->setAdapter(new Client\Adapter\Curl());
        $client->setEncType('multipart/form-data');
        $response = $client->dispatch($request);

        parse_str($response->getContent(), $data);

        if (!isset($data['error']) && isset($data['access_token'])) {
            $accessToken = $data['access_token'];
            $scope = $data['scope'];
            $scope = explode(',', $scope); // sort this crap as GitHub doesn't return it in the same order
            sort($scope);
            $scope = implode(',', $scope);

            if (empty($accessToken) || $scope != 'repo,user') {
                return new Result(Result::FAILURE, null);
            }

            $client = new \Martha\GitHub\Client(['access_token' => $accessToken]);
            $me = $client->me()->user();

            $emails = $client->me()->emails();

            $user = (new User())
                ->setFullName($me['name'])
                ->setAlias($me['login'])
                ->setEmail(current($emails));

            return new Result(Result::SUCCESS, $user);
        }

        return new Result(Result::FAILURE, null);
    }
}
