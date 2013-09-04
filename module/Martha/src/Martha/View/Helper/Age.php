<?php

namespace Martha\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class Age
 * @package Martha\View\Helper
 */
class Age extends AbstractHelper
{
    /**
     * @param \DateTime $age
     * @return string
     */
    public function __invoke(\DateTime $age)
    {
        $diff = $age->diff(new \DateTime());

        if ($diff->d > 6) {
            return $age->format('j M Y');
        } elseif ($diff->d > 0) {
            return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minute' . ($diff->h > 1 ? 's' : '') . ' ago';
        }

        return 'Seconds ago';
    }
}
