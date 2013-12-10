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

/**
 * It is not recommend to change anything in this file. User configurable settings can be found in martha.local.php
 */
return [
    'hateos' => [
        'metadata' => [
            'Martha\Core\Domain\Entity' =>
                realpath(__DIR__ . '/../../vendor/martha-ci/core/src/Martha/Core/Domain/Serializer/Hateoas')
        ]
    ],
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'types' => [
                    'hash' => 'Martha\Core\Persistence\Type\HashType',
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'doctrine_type_mappings' => [
                    'hash' => 'hash',
                ],
            ]
        ],
        'driver' => [
            'martha_driver' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\YamlDriver',
                'cache' => 'array',
                'paths' => [
                    realpath(__DIR__ . '/../../vendor/martha-ci/core/src/Martha/Core/Domain/Entity'),
                    realpath(__DIR__ . '/../../vendor/martha-ci/core/src/Martha/Core/Persistence/Mapping')
                ],
            ],
            'orm_default' => [
                'drivers' => ['Martha\Core\Domain\Entity' => 'martha_driver']
            ]
        ]
    ]
];
