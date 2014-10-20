<?php

namespace Martha\Command;

/**
 * Class CommandTest
 * @package Martha\Command
 */
class CommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that the constructor properly stores the passed command.
     */
    public function testConstructor()
    {
        $command = new Command('foobar');
        $this->assertEquals('foobar', $command->getCommand());
    }

    /**
     * Test adding arguments.
     */
    public function testArguments()
    {
        $command = new Command('foo');
        $command->addArgument('b');
        $command->addArgument('foobizz');

        $this->assertEquals('foo b foobizz', $command->getCommand());
    }

    /**
     * Test adding parameters.
     */
    public function testParameters()
    {
        $command = new Command('fizz');
        $command->addParameter('f', 'gorilla');

        $this->assertEquals('fizz -f gorilla', $command->getCommand());

        $command->addParameter('h', 'industrial');

        $this->assertEquals('fizz -f gorilla -h industrial', $command->getCommand());

        $command->addParameter('icepack', 'refreshing');

        $this->assertEquals(
            'fizz -f gorilla -h industrial --icepack=refreshing',
            $command->getCommand()
        );
    }

    /**
     * Test mixing arguments and parameters.
     */
    public function testArgumentsAndParameters()
    {
        $command = new Command('foo');
        $command->addArgument('juice');
        $command->addParameter('v', 5);
        $command->addParameter('release', 'hounds');
        $command->addArgument('front-door');

        $this->assertEquals(
            'foo juice -v 5 --release=hounds front-door',
            $command->getCommand()
        );
    }
}
