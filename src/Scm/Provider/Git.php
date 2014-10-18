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

        //$command = 'cd ' . $this->repository . ' && git log -n 1 --pretty="%h||%an||%s" ' . $revno;

        //exec($command, $output);


        list($revno, $author, $message) = explode('||', $result->getOutputAsString());
        echo "-- $revno::$author::$message\n";

        $commit = new Commit();
        $commit->setRevisionNumber($revno);
        $commit->setAuthor($author);
        $commit->setMessage($message);

        print_r($commit);

        $commit = new Commit();
    }

    /**
     * @param $fromCommit
     * @param string $toCommit
     * @return \Martha\Scm\ChangeSet\ChangeSet
     */
    public function getChangeSet($fromCommit, $toCommit = '')
    {
        $command = 'cd ' . $this->repository . ' && git log --pretty="%h||%an||%s" ';
        $arguments = '';

        if (empty($toCommit)) {
            $command .= '-n 1 ';
            $arguments .= $fromCommit;
        } else {
            $arguments .= $fromCommit . '..' . $toCommit;
        }

        $command .= $arguments;

        echo "Running: {$command}\n";

        exec($command, $output);

        $changeSet = new ChangeSet();

        foreach ($output as $line) {
            list($revno, $author, $message) = explode('||', $line);
            echo "-- $revno::$author::$message\n";

            $commit = new Commit();
            $commit->setRevisionNumber($revno);
            $commit->setAuthor($author);
            $commit->setMessage($message);
        }

        print_r($changeSet);

        return $changeSet;
    }
}
