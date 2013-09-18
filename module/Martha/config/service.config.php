<?php

return [
    'factories' => [
        'RepositoryFactory' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            return new Martha\Core\Persistence\Repository\Factory($entityManager);
        },
        'BuildRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createBuildRepository();
        },
        'ProjectForm' => function (Zend\ServiceManager\ServiceManager $sm) {
            $entityManager = $sm->get('Doctrine\ORM\EntityManager');
            $form = (new Martha\Form\Project\Create())
                ->setHydrator(new DoctrineModule\Stdlib\Hydrator\DoctrineObject($entityManager));
            return $form;
        },
        'ProjectRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createProjectRepository();
        },
        'Navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        'System' => function (Zend\ServiceManager\ServiceManager $sm) {
            return Martha\Core\System::getInstance();
        }
    ]
];
