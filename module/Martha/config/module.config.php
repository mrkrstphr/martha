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
    'router' => [
        'routes' => [
            'dashboard' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Dashboard',
                        'action' => 'index',
                    ],
                ]
            ],
            'errors' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/errors',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Errors',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'create' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/clear',
                            'defaults' => [
                                'action' => 'clear'
                            ]
                        ],
                    ],
                ]
            ],
            'settings' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/settings',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Settings',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => []
            ],
            'web-hook' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/build/web-hook',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Build',
                        'action' => 'web-hook'
                    ]
                ]
            ],
            'projects' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/projects',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Projects',
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'create' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/create',
                            'defaults' => [
                                'action' => 'create'
                            ]
                        ],
                    ],
                    'build' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/build/[:id]',
                            'defaults' => [
                                'action' => 'build'
                            ]
                        ]
                    ],
                    'get-remote' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/get-remote/[:provider]',
                            'defaults' => [
                                'action' => 'get-remote'
                            ]
                        ]
                    ],
                    'view' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/view/[:id]',
                            'defaults' => [
                                'action' => 'view'
                            ]
                        ],
                    ]
                ]
            ],
            'build' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/build/[:action][/:id]',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Build'
                    ]
                ]
            ],
            'register' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/register[/[:action]]',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Registration',
                        'action' => 'index'
                    ]
                ]
            ],
            'login' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/login[/[:action]]',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Login',
                        'action' => 'index'
                    ]
                ]
            ],
            'logout' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/logout',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Login',
                        'action' => 'logout'
                    ]
                ]
            ]
        ],
    ],
    'service_manager' => [
        'abstract_factories' => [
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'aliases' => [
            'translator' => 'MvcTranslator',
        ],
    ],
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'controllers' => require __DIR__ . '/controller.config.php',
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => ['ViewJsonStrategy']
    ],
    'navigation' => [
        'default' => [
            [
                'label' => 'Dashboard',
                'route' => 'dashboard',
            ],
            [
                'label' => 'Projects',
                'route' => 'projects',
                'pages' => [
                    [
                        'label' => 'View All',
                        'route' => 'projects'
                    ],
                    [
                        'label' => 'Create New',
                        'route' => 'projects/create',
                        'requires-login' => true
                    ]
                ]
            ]
        ]
    ]
];
