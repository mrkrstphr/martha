<?php

namespace Api\Controller;

use Martha\Core\Domain\Repository\PluginRepositoryInterface;
use Martha\Core\Domain\Serializer\SerializerInterface;
use Martha\View\Model\RawJsonModel;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

/**
 * Class AvailablePluginsController
 * @package Api\Controller
 */
class AvailablePluginsController extends AbstractActionController
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
     * @return JsonModel
     */
    public function indexAction()
    {
        // todo fixme make betterme
        $availablePlugins = file_get_contents('http://plugins.martha-ci.org/api/plugins');

        $availablePlugins = json_decode($availablePlugins, true);
        $installedPlugins = $this->repository->getAll();
        $plugins = [];

        foreach ($availablePlugins as $index => $availablePlugin) {
            foreach ($installedPlugins as $installedPlugin) {
                if ($availablePlugin['name'] == $installedPlugin->getName()) {
                    unset($availablePlugins[$index]);
                    continue;
                }
                $plugins[] = $availablePlugin;
            }
        }

        return new JsonModel($plugins);
    }
}
