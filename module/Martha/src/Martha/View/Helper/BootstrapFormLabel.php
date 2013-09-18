<?php

namespace Martha\View\Helper;

use Zend\Form\ElementInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class BootstrapFormLabel
 * @package Martha\View\Helper
 */
class BootstrapFormLabel extends AbstractHelper
{
    /**
     * Generates a Bootstrap styled Form Label using ZF's formLabel() helper.
     *
     * @param ElementInterface $element
     * @param array $attributes
     * @return string
     */
    public function __invoke(ElementInterface $element, array $attributes = [])
    {
        $attributes = array_merge(['class' => 'control-label col-lg-2'], $attributes);

        $html = $this->getView()->formLabel()->openTag($attributes) .
            $element->getLabel() .
            $this->getView()->formLabel()->closeTag();

        return $html;
    }
}
