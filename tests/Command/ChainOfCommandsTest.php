<?php

namespace Martha\Command;

/**
 * Class ChainOfCommandsTest
 * @package Martha\Command
 */
class ChainOfCommandsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test the adding and getting commands both work properly.
     */
    public function testAddCommand()
    {
        $chain = new ChainOfCommands();

        $this->assertCount(0, $chain->getCommands());

        $command = new Command('foo');
        $chain->addCommand($command);
        $commands = $chain->getCommands();

        $this->assertCount(1, $commands);

        $this->assertEquals($command, $commands[0]);
    }

    /**
     * Test the a ChainOfCommands properly generates the string command.
     */
    public function testToString()
    {
        $foo = new Command('foo');
        $bar = new Command('bar');

        $chain = new ChainOfCommands();
        $chain->addCommand($foo);

        $this->assertEquals((string)$foo, (string)$chain);

        $chain->addCommand($bar);

        $this->assertEquals($foo . ' && ' . $bar, (string)$chain);
    }
}
