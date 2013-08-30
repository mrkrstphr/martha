<?php

return [
    'invokables' => [
        'Martha\Controller\Build' => 'Martha\Controller\BuildController'
    ],
    'factories' => [
        'Martha\Controller\Dashboard' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\DashboardController(
                $cm->getServiceLocator()->get('ProjectRepository')
            );
        },
    ]
];
