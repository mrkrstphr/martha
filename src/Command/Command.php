<?php

namespace Martha\Command;

/**
 * Class Command
 * @package Martha\Command
 */
class Command implements RunnableInterface
{
    /**
     * @var string
     */
    protected $command;

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @param string $command
     */
    public function __construct($command)
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function addArgument($name)
    {
        $this->command .= " {$name}";
        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addParameter($name, $value)
    {
        if (strlen($name) == 1) {
            $this->command .= " -{$name}";
            if ($value) {
                $this->command .= " {$value}";
            }
        } else {
            $this->command .= " --{$name}";
            if ($value) {
                $this->command .= "={$value}";
            }
        }

        return $this;
    }

    /**
     * @param RunnableInterface $command
     * @return Result
     */
    public static function run(RunnableInterface $command)
    {
        $output = '';
        $returnValue = 0;

        exec($command, $output, $returnValue);

        $result = new Result($returnValue, $output);

        return $result;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->command;
    }
}
