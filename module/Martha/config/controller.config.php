<?php

return [
    'factories' => [
        'Martha\Controller\Build' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\BuildController(
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
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
