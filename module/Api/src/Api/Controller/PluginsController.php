<?php

namespace Api\Controller;

use Martha\Core\Domain\Repository\PluginRepositoryInterface;
use Martha\Core\Domain\Serializer\SerializerInterface;
use Martha\View\Model\RawJsonModel;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class PluginsController
 * @package Api\Controller
 */
class PluginsController extends AbstractActionController
{
    /**
     * @var \Martha\Core\Domain\Serializer\SerializerInterface
     */
    protected $serializer;

    /**
     * @var \Martha\Core\Domain\Repository\PluginRepositoryInterface
     */
    protected $repository;

    /**
     * @param SerializerInterface $serializer
     * @param PluginRepositoryInterface $repository
     */
    public function __construct(SerializerInterface $serializer, PluginRepositoryInterface $repository)
    {
        $this->serializer = $serializer;
        $this->repository = $repository;
    }

    /**
     * @return RawJsonModel
     */
    public function indexAction()
    {
        $plugins = $this->repository->getBy([], ['name' => 'ASC']);
        return new RawJsonModel($this->serializer->serialize($plugins, 'json'));
    }

    /**
     * @return RawJsonModel
     */
    public function viewAction()
    {
        $plugin = $this->repository->getById($this->params()->fromRoute('id'));
        return new RawJsonModel($this->serializer->serialize($plugin, 'json'));
    }
}
