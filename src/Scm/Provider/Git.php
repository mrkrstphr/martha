<?php

namespace Martha\Scm\Provider;

use Martha\Command\ChainOfCommands;
use Martha\Command\Command;
use Martha\Scm\ChangeSet\ChangeSet;
use Martha\Scm\Commit;

/**
 * Class Git
 * @package Martha\Scm\Provider
 */
class Git extends AbstractProvider
{
    /**
     * @param string $cloneToPath
     * @return boolean
     */
    public function cloneRepository($cloneToPath)
    {
        $cdCommand = (new Command('cd'))
            ->addArgument($cloneToPath);

        $cloneCommand = (new Command('git clone'))
            ->addArgument($this->repository)
            ->addArgument('.');

        $chain = (new ChainOfCommands())
            ->addCommand($cdCommand)
            ->addCommand($cloneCommand);

        $this->repository = $cloneToPath;

        return Command::run($chain)->getReturnValue() == 0;
    }

    public function checkout($ref)
    {
        $cdCommand = (new Command('cd'))
            ->addArgument($this->repository);

        $checkoutCommand = (new Command('git checkout'))
            ->addArgument($ref);

        $chain = (new ChainOfCommands())
            ->addCommand($cdCommand)
            ->addCommand($checkoutCommand);

        Command::run($chain);
    }

    /**
     * @return array
     */
    public function getBranches()
    {
        return [];
    }

    /**
     * @param string $revno
     * @return Commit
     */
    public function getCommit($revno)
    {
        $cdCommand = (new Command('cd'))
            ->addArgument($this->repository);

        $logCommand = (new Command('git'))
            ->addArgument('log')
            ->addParameter('n', 1)
            ->addParameter('pretty', '"%h||%an||%s"')
            ->addArgument($revno);

        $chainOfCommands = (new ChainOfCommands())
            ->addCommand($cdCommand)
            ->addCommand($logCommand);

        $result = Command::run($chainOfCommands);


        list($revno, $author, $message) = explode('||', $result->getOutputAsString());
        echo "-- $revno::$author::$message\n";

        $commit = new Commit();
        $commit->setRevisionNumber($revno);
        $commit->setAuthor($author);
        $commit->setMessage($message);
        
        $commit = new Commit();
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
