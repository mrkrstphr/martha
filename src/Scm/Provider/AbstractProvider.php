<?php

namespace Martha\Scm\Provider;

use Martha\Core\Domain\Entity\User;

/**
 * Class AbstractProvider
 * @package Martha\Scm\Provider
 */
abstract class AbstractProvider
{
    /**
     * @var string
     */
    protected $repository;

    /**
     * @param $repository
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string $repository
     * @return $this
     */
    public function setRepository($repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Makes a local copy of a repository.
     *
     * @param User $user
     * @param $cloneToPath string
     */
    abstract public function cloneRepository(User $user, $cloneToPath);

    /**
     * @param string $ref
     */
    abstract public function checkout($ref);

    /**
     * @return array
     */
    abstract public function getBranches();

    /**
     * @param string $startingCommit
     * @return array
     */
    abstract public function getHistory($startingCommit = '');
}
