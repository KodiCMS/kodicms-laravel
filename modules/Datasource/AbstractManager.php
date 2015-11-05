<?php

namespace KodiCMS\Datasource;

use KodiCMS\Datasource\Contracts\DatasourceManagerInterface;

class AbstractManager implements DatasourceManagerInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $types = [];

    /**
     * @param string $type
     *
     * @return bool
     */
    public function typeExists($type)
    {
        return isset($this->types[$type]);
    }

    /**
     * @return array
     */
    public function getAvailableTypes()
    {
        return $this->types;
    }

    /**
     * @return array
     */
    public function getAvailableTypesForSelect()
    {
        $types = [];

        foreach ($this->getAvailableTypes() as $key => $typeObject) {
            $types[$key] = $typeObject->getTitle();
        }

        return $types;
    }

    /**
     * @param $type
     *
     * @return SectionType|null
     */
    public function getTypeObject($type)
    {
        foreach ($this->getAvailableTypes() as $object) {
            if ($type == $object->getType()) {
                return $object;
            }
        }

        return;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return FieldType|null
     */
    public function getFieldTypeBy($key, $value)
    {
        foreach ($this->getAvailableTypes() as $object) {
            $method = 'get'.ucfirst($key);
            if ($value == $object->{$method}()) {
                return $object;
            }
        }

        return;
    }

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function getTypeByClassName($class)
    {
        return is_null($object = $this->getFieldTypeBy('class', $class))
            ? null
            : $object->getType();
    }

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function getClassNameByType($type)
    {
        return is_null($object = $this->getFieldTypeBy('type', $type))
            ? null
            : $object->getClass();
    }
}
