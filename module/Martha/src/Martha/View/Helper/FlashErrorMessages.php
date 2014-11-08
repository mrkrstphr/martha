<?php

namespace Martha\View\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Helper\AbstractHelper;

/**
 * Class FlashErrorMessages
 * @package Martha\View\Helper
 */
class FlashErrorMessages extends AbstractHelper
{
    public function __invoke()
    {
        $plugin = new FlashMessenger();

        $messages = $plugin->getMessagesFromNamespace('error');

        if (empty($messages)) {
            return '';
        }

        return '<div class="alert alert-danger alert-dismissible" role="alert">
          <button type="button" class="close" data-dismiss="alert">
          <span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          ' . implode('. ', $messages) . '
        </div>';
    }
}
