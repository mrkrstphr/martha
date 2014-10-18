<?php

namespace Martha\Command;

/**
 * Class ChainOfCommands
 *
 * A chain of commands is simply a collection of commands that get concatenated
 * together and run at the same time, resulting in something like:
 *
 *  cd /foo && ls -l
 *
 * Both "cd /foo" and "ls -l" are two separate commands added to the chain.
 *
 * @package Martha\Command
 */
class ChainOfCommands implements RunnableInterface
{
    /**
     * @var array
     */
    protected $commands = array();

    /**
     * Add a new command to this chain.
     *
     * @param Command $command
     * @return $this
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
        return $this;
    }

    /**
     * Retrieve the commands in this chain.
     *
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $chain = '';

        foreach ($this->commands as $command) {
            $chain .= !empty($chain) ? ' && ' : '';
            $chain .= $command;
        }

        return $chain;
    }
}
