<?php

namespace Martha\View\Helper;

use Martha\Core\Domain\Repository\LogRepositoryInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class ErrorCount
 * @package Martha\View\Helper
 */
class ErrorCount extends AbstractHelper
{
    /**
     * @var \Martha\Core\Domain\Repository\LogRepositoryInterface
     */
    protected $repository;

    /**
     * @param LogRepositoryInterface $repository
     */
    public function __construct(LogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return int
     */
    public function __invoke()
    {
        return count($this->repository->getBy(['read' => false]));
    }
}
