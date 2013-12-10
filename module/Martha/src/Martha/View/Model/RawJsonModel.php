<?php

namespace Martha\View\Model;

use Zend\View\Model\JsonModel;

/**
 * Class RawJsonModel
 * @package Martha\View\Model
 */
class RawJsonModel extends JsonModel
{
    /**
     * @param string $json
     * @return $this
     */
    public function setVariables($json)
    {
        $this->variables['json'] = $json;
        return $this;
    }

    /**
     * @return string
     */
    public function serialize()
    {
        return $this->variables['json'];
    }
}
