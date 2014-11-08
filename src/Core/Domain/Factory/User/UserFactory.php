<?php

namespace Martha\Core\Domain\Factory\User;

use Martha\Core\Authentication\AuthenticationResult;
use Martha\Core\Domain\Entity\User;

/**
 * Class UserFactory
 * @package Martha\Core\Domain\Factory\User
 */
class UserFactory
{
    /**
     * @param AuthenticationResult $authResult
     * @return User
     */
    public function createFromAuthenticationResult(AuthenticationResult $authResult)
    {
        $user = new User();
        $user->setAlias($authResult->getAlias());
        $user->setFullName($authResult->getName());

        foreach ($authResult->getEmails() as $email) {
            $user->addEmail(
                (new User\Email())->setEmail($email)
            );
        }

        $user->addToken(
            (new User\Token())
                ->setService($authResult->getService())
                ->setToken($authResult->getCredentials())
        );

        return $user;
    }
}
