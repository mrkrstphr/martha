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
    'invokables' => [
        'Martha\Controller\Registration' => 'Martha\Controller\RegistrationController',
        'Martha\Controller\Settings' => 'Martha\Controller\SettingsController'
    ],
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
        'Martha\Controller\Errors' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\ErrorController(
                $cm->getServiceLocator()->get('ErrorRepository')
            );
        },
        'Martha\Controller\Login' => function (Zend\Mvc\Controller\ControllerManager $cm) {
            return new Martha\Controller\LoginController(
                $cm->getServiceLocator()->get('UserRepository')
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
        }
    ]
];
