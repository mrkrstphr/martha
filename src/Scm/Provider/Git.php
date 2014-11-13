<?php

namespace Martha\Scm\Provider;

/**
 * Class Git
 * @package Martha\Scm\Provider
 */
class Git extends AbstractProvider
{
    /**
     * @todo switch to symfony process
     * @param string $cloneToPath
     * @return boolean
     */
    public function cloneRepository($cloneToPath)
    {
        $command = 'cd ' . $cloneToPath . ' && ' .
            'git clone ' . $this->repository . ' .';

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
