<?php

namespace Martha\View\Helper;

use Martha\Core\Domain\Entity\Build\Alert;
use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class DisplayAlert
 * @package Martha\View\Helper
 */
class DisplayAlert extends AbstractHelper
{
    /**
     * @var string
     */
    protected $template = '<div class="alert %s"><i class="fa %s"></i> %s</div>';

    /**
     * @param Alert $alert
     * @return string
     */
    public function __invoke(Alert $alert)
    {
        if (method_exists($this, $alert->getType())) {
            return $this->{$alert->getType()}($alert);
        }

        return '';
    }

    /**
     * @param Alert $alert
     * @return string
     */
    public function warning(Alert $alert)
    {
        return sprintf($this->template, 'alert-warning', 'fa-warning text-warning', $alert->getDescription());
    }

    /**
     * @param Alert $alert
     * @return string
     */
    public function info(Alert $alert)
    {
        return sprintf($this->template, 'alert-info', 'fa-warning text-info', $alert->getDescription());
    }

    /**
     * @param Alert $alert
     * @return string
     */
    public function success(Alert $alert)
    {
        return sprintf($this->template, 'alert-success', 'fa-warning text-success', $alert->getDescription());
    }
}
