<?php

return [
    'invokables' => [
        'Martha\Controller\Build' => 'Martha\Controller\BuildController'
    ],
    'factories' => [
        'Martha\Controller\Build' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\BuildController(
            );
        },
        'Martha\Controller\Dashboard' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\DashboardController(
                $cm->getServiceLocator()->get('ProjectRepository')
            );
        },
        'Martha\Controller\Projects' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\ProjectsController(
                $cm->getServiceLocator()->get('ProjectRepository')
            );
        },
    ]
];
