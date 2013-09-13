<?php

return [
    'factories' => [
        'BuildRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            return new Martha\Core\Persistence\Repository\BuildRepository($entityManager);
        },
        'ProjectRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            return new Martha\Core\Persistence\Repository\ProjectRepository($entityManager);
        },
        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        'System' => function (Zend\ServiceManager\ServiceManager $sm) {
            $config = $sm->get('Config');
            return Martha\Core\System::getInstance($config['martha']);
        }
    ]
];
