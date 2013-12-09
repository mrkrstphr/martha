<?php

namespace Martha\Controller;

use Martha\Core\Domain\Repository\UserRepositoryInterface;
use Martha\Core\Domain\Serializer\SerializerInterface;
use Martha\View\Model\RawJsonModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class UsersController
 * @package Martha\Controller
 */
class UsersController extends AbstractActionController
{
    /**
     * @var \Martha\Core\Domain\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Martha\Core\Domain\Repository\UserRepositoryInterface
     */
    protected $repository;

    /**
     * @param SerializerInterface $serializer
     * @param UserRepositoryInterface $repository
     */
    public function __construct(SerializerInterface $serializer, UserRepositoryInterface $repository)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @return RawJsonModel
     */
    public function indexAction()
    {
        $users = $this->repository->getBy([], ['fullName' => 'ASC']);
        return new RawJsonModel($this->serializer->serialize($users, 'json'));
    }

    /**
     * @return RawJsonModel
     */
    public function viewAction()
    {
        $user = $this->repository->getById($this->params()->fromRoute('id'));
        return new RawJsonModel($this->serializer->serialize($user, 'json'));
    }
}
