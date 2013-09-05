<?php

return [
    'factories' => [
        'BuildRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('EntityManager');
            return new Martha\Core\Persistence\Repository\BuildRepository($entityManager);
        },
        'EntityManager' => function (Zend\ServiceManager\ServiceManager $sm) {
            $config = $sm->get('Config');

            if (!isset($config['martha']['doctrine'])) {
                throw new \Exception(
                    'Doctrine database connection information is not configured. See ' .
                    '[config/autoload/system.local.php.dist] for a sample configuration.'
                );
            }

            $configManager = new Martha\Core\Persistence\ConfigurationFactory();
            $configManager->loadConfiguration($config['martha']['doctrine']);

            $entityFactory = new Martha\Core\Persistence\EntityManagerFactory($configManager);
            $entity = $entityFactory->getSingleton();

            return $entity;
        },
        'ProjectRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('EntityManager');
            return new Martha\Core\Persistence\Repository\ProjectRepository($entityManager);
        },
        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory'
    ]
];
