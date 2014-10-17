<?php

namespace Martha\View\Helper;

use Martha\Core\Domain\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * Class GravatarUrl
 * @package Martha\View\Helper
 */
class GravatarUrl extends AbstractHelper
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Generate a Gravatar URL for the user.
     *
     * @param User $user
     * @param int $size
     * @return string
     */
    public function __invoke(User $user, $size = 32)
    {
        return 'http://www.gravatar.com/avatar/' . md5($user->getEmail()) . '?s=' . $size .
            '&d=' . urlencode($this->config['martha']['site_url'] . '/images/no-user-icon.png');
    }
}
