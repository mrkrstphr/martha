<?php

namespace Martha\Scm\Provider;

use Martha\Core\Domain\Entity\User;

/**
 * Class Git
 * @package Martha\Scm\Provider
 */
class Git extends AbstractProvider
{
    /**
     * @todo switch to symfony process
     * @param User $user
     * @param string $cloneToPath
     * @return boolean
     */
    public function cloneRepository(User $user, $cloneToPath)
    {
        $key = $user->getPrivateKey();

        // todo fix me no no no
        file_put_contents('tmp/' . $user->getAlias() . '.priv', $key);

        $command = 'ssh-agent ' . $_SERVER['SHELL'] . ' -c \'ssh-add tmp/' . $user->getAlias() . '.priv; git clone ' . $this->repository . ' ' . $cloneToPath . '\'';

        echo $command . "\n\n";

        exec($command, $output, $returnValue);

        // kind of weird, yo
        $this->repository = $cloneToPath;

        return $returnValue == 0;
    }

    /**
     * @todo switch to symfony process
     */
    public function checkout($ref)
    {
        $command = 'cd ' . $this->repository . ' && ' .
            'git checkout ' . $ref;

        exec($command, $output, $returnValue);
    }

    /**
     * @return array
     */
    public function getBranches()
    {
        return [];
    }

    /**
     * @param string $startingCommit
     * @return array
     */
    public function getHistory($startingCommit = '')
    {
        $command = 'cd ' . $this->repository . ' && git log --pretty=format:"%H" -n 100 --skip 1 ' . $startingCommit;

        exec($command, $commits);

        return $commits;
    }
}
