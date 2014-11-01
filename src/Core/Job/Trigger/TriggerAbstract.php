<?php

namespace Martha\Core\Job\Trigger;

use Martha\Scm\Repository;

/**
 * Class TriggerAbstract
 * @package Martha\Core\Job\Trigger
 */
abstract class TriggerAbstract
{
    /**
     * @var Repository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $branch;

    /**
     * @var string
     */
    protected $fork;

    /**
     * @var string
     */
    protected $revisionNumber;

    /**
     * @param \Martha\Scm\Repository $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @param string $branch
     * @return $this
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $fork
     * @return $this
     */
    public function setFork($fork)
    {
        $this->fork = $fork;
        return $this;
    }

    /**
     * @return string
     */
    public function getFork()
    {
        return $this->fork;
    }

    /**
     * @param string $revisionNumber
     * @return $this
     */
    public function setRevisionNumber($revisionNumber)
    {
        $this->revisionNumber = $revisionNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getRevisionNumber()
    {
        return $this->revisionNumber;
    }

    /**
     * @return \Martha\Scm\Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }
}
