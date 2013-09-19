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
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
            );
        },
        'Martha\Controller\Plugin' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\PluginController(
                $cm->getServiceLocator()->get('System')
            );
        },
        'Martha\Controller\Projects' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\ProjectsController(
                $cm->getServiceLocator()->get('System'),
                $cm->getServiceLocator()->get('ProjectRepository'),
                $cm->getServiceLocator()->get('BuildRepository')
            );
        },
    ]
];