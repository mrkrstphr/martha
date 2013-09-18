<?php

namespace Martha\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class BootstrapFormInput
 * @package Martha\View\Helper
 */
class BootstrapFormInput extends AbstractHelper
{
    /**
     * Generates a Bootstrap styled Form Input using ZF's formInput() helper.
     *
     * @param ElementInterface $element
     * @return string
     */
    public function __invoke(ElementInterface $element)
    {
        $element->setAttribute('class', 'form-control');
        return $this->getView()->formInput($element);
    }
}
