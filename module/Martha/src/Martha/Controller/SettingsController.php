<?php

namespace Martha\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class SettingsController
 * @package Martha\Controller
 */
class SettingsController extends AbstractActionController
{
    /**
     * @return array
     */
    public function indexAction()
    {
        return [
            'js' => [
                '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.1/angular.min.js',
                '//cdnjs.cloudflare.com/ajax/libs/angular.js/1.2.1/angular-resource.min.js',
                '//cdnjs.cloudflare.com/ajax/libs/angular-strap/0.7.4/angular-strap.min.js',
                '/js/martha.js',
                '/js/settings.js'
            ],
            'pageTitle' => 'System Settings'
        ];
    }
}
