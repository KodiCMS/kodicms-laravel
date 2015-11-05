<?php

namespace KodiCMS\Support\Model;

use Form;
use KodiCMS\Support\Traits\HtmlAttributes;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;

class ModelFieldLabel
{
    use HtmlAttributes;

    /**
     * @var string
     */
    protected $field;

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
     * @param array       $attributes
     * @param null|string $title
     *
     * @return string
     */
    public function render(array $attributes = [], $title = null)
    {
        $this->setAttributes($attributes);

        if (is_null($title)) {
            $title = $this->field->getTitle();
        }

        return $this->getFormFieldLabel($this->field->getId(), $title, $this->getAttributes());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * @param       $id
     * @param       $title
     * @param array $attributes
     *
     * @return mixed
     */
    protected function getFormFieldLabel($id, $title, array $attributes)
    {
        return Form::label($id, $title, $attributes);
    }
}
