<?php

namespace KodiCMS\Support\Model;

use KodiCMS\Support\Model\Fields\TextField;
use KodiCMS\Support\Model\Fields\CheckboxField;

trait ModelFieldTrait
{
    /**
     * @var ModelFieldCollection
     */
    protected $fieldCollection = null;

    /**
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function renderField($name, array $attributes = [])
    {
        if (is_null($field = $this->getField($name))) {
            $field = $this->makeFormField($name);
        }

        return $field->renderGroup($attributes);
    }

    /**
     * @param string $name
     * @param array  $attributes
     *
     * @return string
     */
    public function renderFormField($name, array $attributes = [])
    {
        if (is_null($field = $this->getField($name))) {
            $field = $this->makeFormField($name);
        }

        return $field->render($attributes);
    }

    /**
     * @param string      $name
     * @param array       $attributes
     * @param string|null $title
     *
     * @return string
     */
    public function renderFormLabel($name, array $attributes = [], $title = null)
    {
        if (! is_null($field = $this->getField($name))) {
            return $field->renderLabel($attributes, $title);
        }
    }

    /**
     * @param string $name
     *
     * @return Contracts\ModelFieldInterface
     */
    public function getField($name)
    {
        return $this->getFieldCollection()->getField($name);
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->getFieldCollection()->getFields();
    }

    /**
     * @return ModelFieldCollection
     */
    public function getFieldCollection()
    {
        if (is_null($this->fieldCollection)) {
            $this->createFormFieldCollection();
        }

        return $this->fieldCollection;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected function makeFormField($key)
    {
        if ($this->hasCast($key)) {
            $type = $this->getCastType($key);

            switch ($type) {
                case 'bool':
                case 'boolean':
                    return (new CheckboxField($key))->setModel($this);
                case 'object':
                    break;
                case 'array':
                case 'json':
                    break;

                case 'collection':
                    break;
                case 'int':
                case 'integer':
                case 'real':
                case 'float':
                case 'double':
                case 'string':
                default:
                    break;
            }
        }

        return (new TextField($key))->setModel($this);
    }

    /**
     * @return array
     */
    protected function fieldCollection()
    {
        return [];
    }

    protected function createFormFieldCollection()
    {
        $this->fieldCollection = new ModelFieldCollection($this, $this->fieldCollection());
    }
}
