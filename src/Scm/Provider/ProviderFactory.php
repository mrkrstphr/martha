<?php

namespace Martha\Scm\Provider;

use Martha\Core\Domain\Entity\Project;

/**
 * Class ProviderFactory
 * @package Martha\Scm\Provider
 */
class ProviderFactory
{
    /**
     * @param Project $project
     * @return bool|AbstractProvider
     */
    public static function createForProject(Project $project)
    {
        switch (strtolower($project->getScm())) {
            case 'git':
                return new Git($project->getUri());
                break;
        }

        return false;
    }
}
