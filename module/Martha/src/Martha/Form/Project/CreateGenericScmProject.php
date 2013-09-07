<?php

namespace Martha\Form\Project;

use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

/**
 * Class CreateGenericScmProject
 * @package Martha\Form\Project
 */
class CreateGenericScmProject extends Form
{
    /**
     * Set us up the form!
     *
     * @param string $name
     * @param array $options
     */
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);

        $name = (new Text('name'))
            ->setLabel('* Name:');

        $description = (new Textarea('description'))
            ->setLabel('Description:');

        $scm = (new Select('scm'))
            ->setLabel('Scm:')
            ->setValueOptions(
                [
                    '' => '',
                    'git' => 'Git'
                ]
            );

        $uri = (new Text('uri'))
            ->setLabel('Source URI:');

        $submit = (new Button('create'))
            ->setLabel('Create')
            ->setAttribute('type', 'submit');

        $this->add($name);
        $this->add($description);
        $this->add($scm);
        $this->add($uri);
        $this->add($submit);
    }
}
