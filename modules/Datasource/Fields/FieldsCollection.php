<?php

namespace KodiCMS\Datasource\Fields;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use KodiCMS\Datasource\Contracts\FieldGroupInterface;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\FieldsCollectionInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\FieldGroups\DefaultGroup;
use KodiCMS\Datasource\Model\FieldGroup;

class FieldsCollection implements Arrayable, FieldsCollectionInterface, \Countable, \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $fields = [];

    /**
     * @var array
     */
    protected $fieldIds = [];

    /**
     * @var array
     */
    protected $fieldNames = [];

    /**
     * @var SectionInterface
     */
    protected $section;

    /**
     * @param Collection|array $fields
     */
    public function __construct($fields)
    {
        foreach ($fields as $field) {
            if ($field instanceof FieldInterface) {
                $this->add($field);
            } elseif ($field instanceof FieldGroupInterface) {
                foreach ($field->getFields() as $groupField) {
                    $this->add($groupField);
                }
            }
        }
    }

    /**
     * @param int $id
     *
     * @return FieldInterface|null
     */
    public function getById($id)
    {
        return array_get($this->fieldIds, $id);
    }

    /**
     * @param string $type
     *
     * @return Collection
     */
    public function getByType($type)
    {
        return new Collection(array_filter($this->getFields(), function ($field) use ($type) {
            if (is_array($type)) {
                return in_array($field->type, $type);
            } else {
                return $field->type == $type;
            }
        }));
    }

    /**
     * @param string $key
     *
     * @return FieldInterface|null
     */
    public function getByKey($key)
    {
        return array_get($this->fields, $key);
    }

    /**
     * @return array
     */
    public function getIds()
    {
        return array_keys($this->fieldIds);
    }

    /**
     * @return array
     */
    public function getKeys()
    {
        return array_keys($this->getFields());
    }

    /**
     * @return array
     */
    public function getNames()
    {
        return $this->fieldNames;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @return array
     */
    public function getGroupedFields()
    {
        $groups = FieldGroup::all()->map(function ($field) {
            return $field->setFields([]);
        })->keyBy('id');

        $defaultGroup = (new DefaultGroup())->setFields([]);

        foreach ($this->getFields() as $field) {
            if ($groups->offsetExists($field->group_id) and ! is_null($group = $groups->offsetGet($field->group_id))) {
                $group->addField($field);
            } else {
                $defaultGroup->addField($field);
            }
        }

        return $groups->add($defaultGroup);
    }

    /**
     * @param array| string $keys
     *
     * @return array
     */
    public function getOnly($keys)
    {
        if (! is_array($keys)) {
            $keys = func_get_args();
        }

        return array_only($this->fields, $keys);
    }

    /**
     * @return array
     */
    public function getEditable()
    {
        return new static(array_filter($this->getFields(), function ($field) {
            return $field->isEditable();
        }), $this->section);
    }

    /**
     * @param FieldInterface $field
     *
     * @return $this
     */
    public function add(FieldInterface $field)
    {
        if ($field->exists) {
            $field->initFilterType();
        }

        $this->fields[$field->getDBKey()] = $field;
        $this->fieldIds[$field->getId()] = $field;
        $this->fieldNames[$field->getDBKey()] = $field->getName();

        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->getFields();
    }

    /**
     * @return  int
     */
    public function count()
    {
        return count($this->getFields());
    }

    public function rewind()
    {
        reset($this->fields);
    }

    public function current()
    {
        return current($this->fields);
    }

    /**
     * @return int
     */
    public function key()
    {
        return key($this->fields);
    }

    /**
     * @return Item
     */
    public function next()
    {
        return next($this->fields);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return (! is_null($key = key($this->fields)) and $key !== false);
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * @param string $key
     *
     * @return FieldInterface|null
     */
    public function __get($key)
    {
        return $this->getByKey($key);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists.
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $key <p>
     *                   An offset to check for.
     *                   </p>
     *
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($key)
    {
        return isset($this->fields[$key]);
    }

    /**
     * @param string $key
     *
     * @return FieldInterface|null
     */
    public function offsetGet($key)
    {
        return $this->getByKey($key);
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function offsetSet($key, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        // TODO: Implement offsetUnset() method.
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->fields);
    }
}
