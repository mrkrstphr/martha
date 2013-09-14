<?php

return [
    'factories' => [
        'BuildRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            return new Martha\Core\Persistence\Repository\BuildRepository($entityManager);
        },
        'ProjectForm' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            $form = (new Martha\Form\Project\Create())
                ->setHydrator(new DoctrineModule\Stdlib\Hydrator\DoctrineObject($entityManager));
            return $form;
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
