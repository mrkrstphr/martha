<?php

namespace Martha\Form\Project;

use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

/**
 * Class CreateGitHubProject
 * @package Martha\Form\Project
 */
class CreateGitHubProject extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $projectId = (new Select('project_id'))
            ->setLabel('* Project: ');

        $create = (new Button('create'))
            ->setLabel('Create')
            ->setAttribute('type', 'submit');

        $this->add($projectId);
        $this->add($create);
    }

    /**
     * Load the form up with the list of the user's repositories.
     *
     * @param array $projects
     * @return $this
     */
    public function setProjects(array $projects)
    {
        /**
         * @var $projectId Select
         */
        $projectId = $this->get('project_id');

        $options = ['' => ''];

        foreach ($projects as $project) {
            $options[$project['full_name']] = $project['full_name'];
        }

        $projectId->setValueOptions($options);

        return $this;
    }
}
