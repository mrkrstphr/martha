<?php

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
                ],
            ],
            'build-hook' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/hook/build',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Build',
                        'action' => 'hook'
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
                        ]
                    ],
                    'view' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/view/[:project]',
                            'defaults' => [
                                'action' => 'view'
                            ]
                        ],
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
                        'route' => 'projects/create'
                    ]
                ]
            ]
        ]
    ]
];
