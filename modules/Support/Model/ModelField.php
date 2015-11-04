<?php

namespace KodiCMS\Support\Model;

use KodiCMS\Support\Traits\Settings;
use KodiCMS\Support\Helpers\Callback;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Support\Traits\HtmlAttributes;
use Illuminate\Database\Eloquent\Collection;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;

abstract class ModelField implements ModelFieldInterface
{
    use Settings, HtmlAttributes;

    /**
     * @var int
     */
    protected static $tabIndex = 100;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ModelFieldLabel
     */
    protected $label;

    /**
     * @var ModelFieldGroup
     */
    protected $group;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $modelKey;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * @var mixed
     */
    protected $defaultValue = null;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var bool
     */
    protected $hasInputGroups = false;

    /**
     * @param string     $key
     * @param array|null $attributes
     * @param array|null $settings
     */
    public function __construct($key, array $attributes = null, array $settings = null)
    {
        $this->key = $key;
        $this->modelKey = $key;

        if (! is_null($settings)) {
            $this->setSettings($settings);
        }

        $this->title = ucwords(str_replace(['_'], ' ', $key));

        if (! is_null($attributes)) {
            $this->setAttributes($attributes);
        }

        $this->label = new ModelFieldLabel($this);
        $this->group = new ModelFieldGroup($this);

        $this->boot();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->getAttribute('id', $this->model->getTable().'_'.$this->getKey());
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        if (isset($this->callbackValue)) {
            $this->getCallbackValue();
        }

        $value = $this->getModelValue();

        if (is_null($value)) {
            $value = $this->getDefaultValue();
        }

        return $value;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getModelKey()
    {
        return $this->modelKey;
    }

    /**
     * @return int
     */
    public function getTabIndex()
    {
        return self::$tabIndex++;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function getLabel(array $attributes = null)
    {
        if (! is_null($attributes)) {
            $this->label->setAttributes($attributes);
        }

        return $this->label;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function getGroup(array $attributes = null)
    {
        if (! is_null($attributes)) {
            $this->group->setAttributes($attributes);
        }

        return $this->group;
    }

    /**
     * @return bool
     */
    public function hasAddInputGroup()
    {
        return $this->hasInputGroups;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setSettingAppend($text)
    {
        $this->hasInputGroups = true;
        $this->settings['append'] = $text;

        return $this;
    }

    /**
     * @param string $text
     *
     * @return $this
     */
    public function setSettingPrepend($text)
    {
        $this->hasInputGroups = true;
        $this->settings['prepend'] = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getSettingAppend()
    {
        return $this->addInputGroup(array_get($this->settings, 'append'));
    }

    /**
     * @return string
     */
    public function getSettingPrepend()
    {
        return $this->addInputGroup(array_get($this->settings, 'prepend'));
    }

    /**
     * @param string $text
     *
     * @return null|string
     */
    public function addInputGroup($text)
    {
        if (empty($text)) {
            return;
        }

        $this->hasInputGroups = true;

        return '<span class="input-group-addon">'.$text.'</span>';
    }

    /**
     * @return string
     */
    public function getSettingHelpText()
    {
        if (empty($this->settings['helpText'])) {
            return;
        }

        return '<span class="help-block">'.$this->settings['helpText'].'</span>';
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function group(\Closure $callback)
    {
        $callback($this->getGroup());

        return $this;
    }

    /**
     * @param callable $callback
     *
     * @return $this
     */
    public function label(\Closure $callback)
    {
        $callback($this->getLabel(), $this->getGroup());

        return $this;
    }

    /**
     * @param sreing|null $prefix
     *
     * @return string
     */
    public function getName($prefix = null)
    {
        if (! is_null($prefix)) {
            $this->setPrefix($prefix);
        }

        return empty($this->prefix) ? $this->getKey() : $this->prefix.'['.$this->getKey().']';
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @param string|array $prefix
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        if (is_array($prefix)) {
            $firstSegment = array_shift($prefix);

            if (! empty($prefix)) {
                $prefix = implode('][', $prefix);
                $prefix = $firstSegment.'['.$prefix.']';
            } else {
                $prefix = $firstSegment;
            }
        }

        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @param Model $model
     *
     * @return $this
     */
    public function setModel(Model $model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function setModelKey($key)
    {
        $this->modelKey = $key;

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setDefaultValue($value)
    {
        $this->defaultValue = $value;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function render(array $attributes = [])
    {
        if (method_exists($this, 'beforeLabelRender')) {
            $this->beforeLabelRender();
        }

        $this->setAttributes($attributes);

        if (! isset($this->attributes['id'])) {
            $this->attributes['id'] = $this->getId();
        }

        return $this->getFormFieldHTML($this->getName(), $this->getValue(), $this->getAttributes());
    }

    /**
     * @param array       $attributes
     * @param null|string $title
     *
     * @return string
     */
    public function renderLabel(array $attributes = [], $title = null)
    {
        if (method_exists($this, 'beforeLabelRender')) {
            $this->beforeLabelRender();
        }

        return $this->getLabel()->render($attributes, $title);
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function renderGroup(array $attributes = [])
    {
        if (method_exists($this, 'beforeRender')) {
            $this->beforeRender();
        }

        return $this->getGroup()->render($attributes);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->renderGroup();
    }

    /**
     * @return mixed
     */
    protected function getCallbackValue()
    {
        return Callback::invoke($this->callbackValue, $this->callbackParameters);
    }

    /**
     * @return mixed
     */
    protected function getModelValue()
    {
        $value = $this->model->getAttribute($this->getModelKey());

        if ($value instanceof Model) {
            $value = $value->getAttribute($value->getKeyName());
        } elseif ($value instanceof Collection) {
            $value = $value->lists('id')->all();
        }

        return $value;
    }

    protected function boot()
    {
    }

    protected function beforeRender()
    {
    }

    /**
     * @param string $name
     * @param mixed  $value
     * @param array  $attributes
     *
     * @return mixed
     */
    abstract protected function getFormFieldHTML($name, $value, array $attributes);
}
