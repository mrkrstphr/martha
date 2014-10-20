<?php

namespace Martha\Plugin\GitHub\WebHook;

use Martha\Core\Job\Trigger\TriggerAbstract;

/**
 * Class WebHook
 * @package Martha\Plugin\GitHub\WebHook
 */
class WebHook extends TriggerAbstract
{
    /**
     * @var array
     */
    protected $hook;

    /**
     * @param array $hook
     * @return $this
     */
    public function setHook(array $hook)
    {
        $this->hook = $hook;
        return $this;
    }

    /**
     * @return array
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @return string
     */
    public function getFullProjectName()
    {
        return $this->hook['repository']['owner']['name'] . '/' . $this->hook['repository']['name'];
    }
}
