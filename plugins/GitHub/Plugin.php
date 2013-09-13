<?php

namespace Martha\Plugin\GitHub;

use Martha\Core\Plugin\AbstractPlugin;

/**
 * Class Plugin
 * @package Martha\Plugin\GitHub
 */
class Plugin extends AbstractPlugin
{
    public function init()
    {
        $this->getPluginManager()->registerRemoteProjectProvider('Martha\Plugin\GitHub\RemoteProjectProvider');
    }
}
