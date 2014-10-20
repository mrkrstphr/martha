<?php

namespace Martha\Scm\ChangeSet;

use Martha\Scm\Commit;

/**
 * Class ChangeSet
 * @package Martha\Scm\ChangeSet
 */
class ChangeSet
{
    /**
     * @var array
     */
    protected $commits = [];

    /**
     * @var array
     */
    protected $changedFiles;

    /**
     * @param array $commits
     * @return $this
     */
    public function setCommits(array $commits)
    {
        $this->commits = $commits;
        return $this;
    }

    /**
     * @param Commit $commit
     * @return $this
     */
    public function addCommit(Commit $commit)
    {
        $this->commits[] = $commit;
        return $this;
    }

    /**
     * @return array
     */
    public function getCommits()
    {
        return $this->commits;
    }

    /**
     * @param array $changedFiles
     */
    public function setChangedFiles($changedFiles)
    {
        $this->changedFiles = $changedFiles;
    }

    /**
     * @return array
     */
    public function getChangedFiles()
    {
        return $this->changedFiles;
    }

    /**
     * @return Commit
     */
    public function getNewestCommit()
    {
        $maxDate = null;
        $commit = null;

        /**
         * @var Commit $testCommit
         */
        foreach ($this->commits as $testCommit) {
            if (is_null($maxDate) || $maxDate->diff($testCommit->getDate())->invert == 0) {
                $maxDate = $testCommit->getDate();
                $commit = $testCommit;
            }
        }

        return $commit;
    }
}
