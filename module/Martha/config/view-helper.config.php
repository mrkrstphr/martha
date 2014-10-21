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
        'errorCount' => function (Zend\View\HelperPluginManager $pm) {
            return new Martha\View\Helper\ErrorCount($pm->getServiceLocator()->get('ErrorRepository'));
        },
        'gravatarUrl' => function (Zend\View\HelperPluginManager $pm) {
            return new Martha\View\Helper\GravatarUrl($pm->getServiceLocator()->get('Config'));
        },
    ],
    'invokables' => [
        'age' => 'Martha\View\Helper\Age',
        'buildStatus' => 'Martha\View\Helper\BuildStatus',
        'bootstrapFormLabel' => 'Martha\View\Helper\BootstrapFormLabel',
        'bootstrapFormInput' => 'Martha\View\Helper\BootstrapFormInput',
        'bootstrapFormSelect' => 'Martha\View\Helper\BootstrapFormSelect',
        'displayAlert' => 'Martha\View\Helper\DisplayAlert'
    ]
];
