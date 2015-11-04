<?php

namespace KodiCMS\Support\Model;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use KodiCMS\Support\Model\Contracts\ModelFieldInterface;
use KodiCMS\Support\Model\Contracts\ModelFieldsInterface;
use KodiCMS\Support\Model\Contracts\ModelFieldCollectionInterface;

class ModelFieldCollection extends Collection
{
    /**
     * @var ModelFieldCollectionInterface
     */
    protected $items = [];

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var ModelFieldsInterface
     */
    protected $collection;

    /**
     * @param Model                $model
     * @param ModelFieldsInterface $collection
     *
     * @throws ModelFieldCollectionException
     */
    public function __construct(Model $model, ModelFieldsInterface $collection)
    {
        $this->model = $model;
        $this->collection = $collection;

        foreach ($collection->fields() as $field) {
            $this->addField($field);
        }
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->items;
    }

    /**
     * @param string $name
     *
     * @return ModelFieldInterface
     */
    public function getField($name)
    {
        $related = null;

        if (strpos($name, '::') !== false) {
            list($name, $related) = explode('::', $name, 2);
        }

        if (is_null($field = array_get($this->items, $name))) {
            return;
        }

        if (! is_null($related) and method_exists($field, 'getRelatedModel')) {
            $relationship = $field->getRelatedModel();

            if ($relationship instanceof Model) {
                return $relationship->getField($related);
            } elseif ($relationship instanceof Relation) {
                if (is_null($model = $relationship->getResults())) {
                    $model = $relationship->getRelated();
                }

                return $model->getField($related)->setPrefix($name);
            }
        }

        return $field;
    }

    /**
     * @param ModelFieldInterface $field
     *
     * @return ModelFieldInterface
     */
    public function addField(ModelFieldInterface $field)
    {
        $field->setModel($this->model);

        return $this->items[$field->getKey()] = $field;
    }

    /**
     * @param string $name
     *
     * @return mixed|null
     */
    public function getFieldValue($name)
    {
        if (is_null($field = $this->getField($name))) {
            return;
        }

        return $field->getValue();
    }

    /**
     * @param string $prefix
     *
     * @return $this
     */
    public function setFieldPrefix($prefix)
    {
        foreach ($this->items as $filed) {
            $filed->setPrefix($prefix);
        }

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setFieldAttributes(array $attributes)
    {
        foreach ($this->items as $filed) {
            $filed->setAttributes($attributes);
        }

        return $this;
    }

    /**
     * @param array $attributes
     *
     * @return $this
     */
    public function setFieldLabelAttributes(array $attributes)
    {
        foreach ($this->items as $filed) {
            $filed->setLabelAttributes($attributes);
        }

        return $this;
    }
}
