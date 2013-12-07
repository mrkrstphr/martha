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
            'js' => '/js/view/settings.js',
            'pageTitle' => 'System Settings'
        ];
    }
}
