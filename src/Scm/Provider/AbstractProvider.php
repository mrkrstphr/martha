<?php

namespace Martha\Scm\Provider;

use Martha\Core\Service\Build\Environment;

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
     * @var Environment
     */
    protected $environment;

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
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param Environment $environment
     * @return $this
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Makes a local copy of a repository.
     *
     * @param $cloneToPath string
     */
    abstract public function cloneRepository($cloneToPath);

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
