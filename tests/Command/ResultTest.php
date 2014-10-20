<?php

namespace Martha\Command;

/**
 * Class Result
 * @package Martha\Command
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that a return value is stored and retrieved properly.
     */
    public function testReturnValue()
    {
        $result = new Result(15, []);

        $this->assertEquals(15, $result->getReturnValue());
    }

    /**
     * Test that the output is stored and retrieved properly.
     */
    public function testGetOutput()
    {
        $ret = ['foo' => 'bar', 'bazz' => 101];
        $result = new Result(0, $ret);

        $this->assertEquals($ret, $result->getOutput());
    }

    /**
     * Test that getting the output as a string works correctly.
     */
    public function testGetOutputAsString()
    {
        $ret = ['foo' => 'bar', 'bazz' => 101];
        $result = new Result(0, $ret);

        $this->assertEquals("bar\n101", $result->getOutputAsString());
    }

    /**
     * Test that __toString() as a string works correctly.
     */
    public function testToString()
    {
        $ret = ['foo' => 'bar', 'bazz' => 101];
        $result = new Result(0, $ret);

        $this->assertEquals("bar\n101", (string)$result);
    }
}
