<?php

namespace Martha\Scm\Provider;

/**
 * Class GitTest
 */
class GitTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     */
    public function testGetChangeSet()
    {
        $git = new Git(TEST_BASE . '/scm-git');

        $changeSet = $git->getCommit('ebd7553589b5e89501e4a27f3847c5826d38058e');
    }
}
