<?php

namespace Martha\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class BuildStatus
 * @package Martha\View\Helper
 */
class BuildStatus extends AbstractHelper
{
    /**
     * @param string $status
     * @return string
     */
    public function __invoke($status)
    {
        $statusClassMap = [
            'success' => 'label-success',
            'failure' => 'label-danger',
            'building' => 'label-info'
        ];

        $statusKey = strtolower($status);

        if (isset($statusClassMap[$statusKey])) {
            $class = $statusClassMap[$statusKey];
        } else {
            $class = 'label-default';
        }

        return '<span class="label ' . $class . '">' . ucwords($status) . '</span>';
    }
}
