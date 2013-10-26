<?php

namespace Martha\View\Model;

use Martha\Core\Plugin\View\AbstractView;
use Zend\View\Model\ModelInterface;

/**
 * Class MarthaViewAdapter
 * @package Martha\View
 */
class MarthaViewAdapter implements ModelInterface
{
    /**
     * @var AbstractView
     */
    protected $view;

    /**
     * @var array
     */
    protected $options;

    /**
     * Is this a standalone, or terminal, model?
     *
     * @var bool
     */
    protected $terminate = false;

    /**
     * Is this append to child  with the same capture?
     *
     * @var bool
     */
    protected $append = false;

    /**
     * What variable a parent model should capture this model to
     *
     * @var string
     */
    protected $captureTo = 'content';

    /**
     * @param AbstractView $view
     */
    public function __construct(AbstractView $view)
    {
        $this->view = $view;
    }

    /**
     * Property overloading: set variable value
     *
     * @param  string $name
     * @param  mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->setVariable($name, $value);
    }

    /**
     * Property overloading: get variable value
     *
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (!$this->__isset($name)) {
            return null;
        }

        $variables = $this->getVariables();
        return $variables[$name];
    }

    /**
     * Property overloading: do we have the requested variable value?
     *
     * @param  string $name
     * @return bool
     */
    public function __isset($name)
    {
        $variables = $this->getVariables();
        return isset($variables[$name]);
    }

    /**
     * Property overloading: unset the requested variable
     *
     * @param  string $name
     * @return void
     */
    public function __unset($name)
    {
        if (!$this->__isset($name)) {
            return null;
        }

        unset($this->variables[$name]);
    }

    /**
     * Set a single option
     *
     * @param  string $name
     * @param  mixed $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        $this->options[(string) $name] = $value;
        return $this;
    }

    /**
     * Get a single option
     *
     * @param  string       $name           The option to get.
     * @param  mixed|null   $default        (optional) A default value if the option is not yet set.
     * @return mixed
     */
    public function getOption($name, $default = null)
    {
        $name = (string) $name;
        return array_key_exists($name, $this->options) ? $this->options[$name] : $default;
    }

    /**
     * Set renderer options/hints en masse
     *
     * @param array|Traversable $options
     * @throws \Zend\View\Exception\InvalidArgumentException
     * @return ViewModel
     */
    public function setOptions($options)
    {
        // Assumption is that lowest common denominator for renderer configuration
        // is an array
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        if (!is_array($options)) {
            throw new Exception\InvalidArgumentException(sprintf(
                '%s: expects an array, or Traversable argument; received "%s"',
                __METHOD__,
                (is_object($options) ? get_class($options) : gettype($options))
            ));
        }

        $this->options = $options;
        return $this;
    }

    /**
     * Get renderer options/hints
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Clear any existing renderer options/hints
     *
     * @return $this
     */
    public function clearOptions()
    {
        $this->options = array();
        return $this;
    }

    /**
     * Get a single view variable
     *
     * @param  string       $name
     * @param  mixed|null   $default (optional) default value if the variable is not present.
     * @return mixed
     */
    public function getVariable($name, $default = null)
    {
        return $this->view->getVariable($name, $default);
    }

    /**
     * Set view variable
     *
     * @param  string $name
     * @param  mixed $value
     * @return $this
     */
    public function setVariable($name, $value)
    {
        $this->view->setVariable($name, $value);
        return $this;
    }

    /**
     * Set view variables en masse
     *
     * @param  array|\ArrayAccess $variables
     * @return ModelInterface
     */
    public function setVariables($variables)
    {
        $this->view->setVariables($variables);
        return $this;
    }

    /**
     * Get view variables
     *
     * @return array|\ArrayAccess
     */
    public function getVariables()
    {
        return $this->view->getVariables();
    }

    /**
     * Set the template to be used by this model
     *
     * @param  string $template
     * @return ModelInterface
     */
    public function setTemplate($template)
    {
        $this->view->setViewFile($template);
    }

    /**
     * Get the template to be used by this model
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->view->getViewFile();
    }

    /**
     * Add a child model
     *
     * @throws \Exception
     * @param  ModelInterface $child
     * @param  null|string $captureTo Optional; if specified, the "capture to" value to set on the child
     * @param  null|bool $append Optional; if specified, append to child  with the same capture
     * @return ModelInterface
     */
    public function addChild(ModelInterface $child, $captureTo = null, $append = false)
    {
        throw new \Exception('Child views not implemented');
    }

    /**
     * Return all children.
     *
     * Return specifies an array, but may be any iterable object.
     *
     * @return array
     */
    public function getChildren()
    {
        return [];
    }

    /**
     * Does the model have any children?
     *
     * @return bool
     */
    public function hasChildren()
    {
        return false;
    }

    /**
     * Set the name of the variable to capture this model to, if it is a child model
     *
     * @param  string $capture
     * @return $this
     */
    public function setCaptureTo($capture)
    {
        $this->captureTo = (string) $capture;
        return $this;
    }

    /**
     * Get the name of the variable to which to capture this model
     *
     * @return string
     */
    public function captureTo()
    {
        return $this->captureTo;
    }

    /**
     * Set flag indicating whether or not this is considered a terminal or standalone model
     *
     * @param  bool $terminate
     * @return $this
     */
    public function setTerminal($terminate)
    {
        $this->terminate = (bool) $terminate;
        return $this;
    }

    /**
     * Is this considered a terminal or standalone model?
     *
     * @return bool
     */
    public function terminate()
    {
        return $this->terminate;
    }

    /**
     * Set flag indicating whether or not append to child  with the same capture
     *
     * @param  bool $append
     * @return $this
     */
    public function setAppend($append)
    {
        $this->append = (bool) $append;
        return $this;
    }

    /**
     * Is this append to child  with the same capture?
     *
     * @return bool
     */
    public function isAppend()
    {
        return $this->append;
    }

    /**
     * Return count of children
     *
     * @return int
     */
    public function count()
    {
        return 0;
    }

    /**
     * Get iterator of children
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator([]);
    }
}
