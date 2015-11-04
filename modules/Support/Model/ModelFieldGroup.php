<?php

namespace KodiCMS\Support\Model;

use Form;
use KodiCMS\Support\Traits\Settings;
use KodiCMS\Support\Traits\HtmlAttributes;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;

class ModelFieldGroup
{
    use Settings, HtmlAttributes;

    /**
     * @var string
     */
    protected $template = null;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @param ModelFieldInterface $field
     * @param array               $attributes
     */
    public function __construct(ModelFieldInterface $field, array $attributes = null)
    {
        $this->field = $field;

        if (! is_null($attributes)) {
            $this->setAttributes($attributes);
        }
    }

    /**
     * @return $this
     */
    public function setSizeLg()
    {
        return $this->setAttribute('class', 'form-group-lg');
    }

    /**
     * @return $this
     */
    public function setSizeXs()
    {
        return $this->setAttribute('class', 'form-group-xs');
    }

    /**
     * @return $this
     */
    public function setSizeSm()
    {
        return $this->setAttribute('class', 'form-group-sm');
    }

    /**
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function render(array $attributes = [])
    {
        return view($this->template, [
            'group' => $this,
            'model' => $this->field->getModel(),
            'field' => $this->field->setAttributes($attributes),
            'label' => $this->field->getLabel(),
        ])->render();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
