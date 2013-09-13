<?php

namespace Martha\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class BootstrapFormSelect
 * @package Martha\View\Helper
 */
class BootstrapFormSelect extends AbstractHelper
{
    /**
     * Generates a Bootstrap styled Form Select using ZF's formSelect() helper.
     *
     * @param ElementInterface $element
     * @return string
     */
    public function __invoke(ElementInterface $element)
    {
        $element->setAttribute('class', 'form-control');
        return $this->getView()->formSelect($element);
    }
}
