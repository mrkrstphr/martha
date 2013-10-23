<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <https://github.com/martha-ci>.
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
                ],
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
                    'route' => '/build/view/[:id]',
                    'defaults' => [
                        'controller' => 'Martha\Controller\Build',
                        'action' => 'view'
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
