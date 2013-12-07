<?php
/*
 * Copyright (C) 2013 Kristopher Wilson <kristopherwilson@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 * IN THE SOFTWARE.
 */

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
        'ErrorRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createErrorRepository();
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
        },
        'UserRepository' => function (Zend\ServiceManager\ServiceManager $sm) {
            $factory = $sm->get('RepositoryFactory');
            return $factory->createUserRepository();
        },
    ]
];
