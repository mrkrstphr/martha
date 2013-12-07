<?php

namespace Martha\View\Helper;

use Martha\Core\Domain\Repository\ErrorRepositoryInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ErrorCount
 * @package Martha\View\Helper
 */
class ErrorCount extends AbstractHelper
{
    /**
     * @var \Martha\Core\Domain\Repository\ErrorRepositoryInterface
     */
    protected $repository;

    /**
     * @param ErrorRepositoryInterface $repository
     */
    public function __construct(ErrorRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return count($this->repository->getBy(['wasRead' => false]));
    }
}
