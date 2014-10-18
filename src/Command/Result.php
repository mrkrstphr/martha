<?php

namespace Martha\Command;

/**
 * Class Result
 * @package Martha\Command
 */
class Result
{
    /**
     * @var int
     */
    protected $returnValue;

    /**
     * @var array
     */
    protected $output;

    /**
     * @param int $returnValue
     * @param array $output
     */
    public function __construct($returnValue, array $output)
    {
        $this->returnValue = $returnValue;
        $this->output = $output;
    }

    /**
     * @return int
     */
    public function getReturnValue()
    {
        return $this->returnValue;
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return string
     */
    public function getOutputAsString()
    {
        return implode("\n", $this->output);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getOutputAsString();
    }
}
