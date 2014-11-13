<?php

namespace Martha\Form\Project;

use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * Class Create
 * @package Martha\Form\Project
 */
class Create extends Form implements InputFilterProviderInterface
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

        $type = (new Select('project_type'))
            ->setLabel('* Project Type')
            ->setValueOptions(
                [
                    '' => '',
                    'generic' => 'Generic SCM Project'
                ]
            );

        $name = (new Text('name'))
            ->setLabel('* Name:');

        $description = (new Textarea('description'))
            ->setLabel('Description:');

        $scm = (new Select('scm'))
            ->setLabel('* Scm:')
            ->setValueOptions(
                [
                    '' => '',
                    'git' => 'Git'
                ]
            );

        $uri = (new Text('uri'))
            ->setLabel('* Source URL:');

        $projectId = (new Select('project_id'))
            ->setLabel('* Project: ')
            ->setDisableInArrayValidator(true)
            ->setValueOptions(
                [
                    '' => '',
                    'martha-ci/core' => 'martha-ci/core'
                ]
            );

        $private = (new Checkbox('private'))
            ->setLabel('Is Private?')
            ->setValue('true');

        $submit = (new Button('create'))
            ->setLabel('Create')
            ->setAttribute('type', 'submit');

        $this->add($type);

        $this->add($name);
        $this->add($description);
        $this->add($scm);
        $this->add($uri);
        $this->add($private);

        $this->add($projectId);

        $this->add($submit);
    }

    /**
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return [
            'project_type' => [
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    ['name' => 'Zend\Filter\Null']
                ]
            ],
            'name' => [
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    ['name' => 'Zend\Filter\Null']
                ]
            ],
            'scm' => [
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    ['name' => 'Zend\Filter\Null']
                ]
            ],
            'uri' => [
                'required' => true,
                'filters' => [
                    ['name' => 'Zend\Filter\StringTrim'],
                    ['name' => 'Zend\Filter\Null']
                ]
            ],
            'project_id' => [
                'required' => false,
                'disable_inarray_validator' => true
            ],
            'private' => [
                'required' => false
            ]
        ];
    }
}
