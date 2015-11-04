<?php

namespace KodiCMS\Datasource\Contracts;

interface DatasourceManagerInterface
{
    /**
     * @param string $type
     *
     * @return bool
     */
    public function typeExists($type);

    /**
     * @return array
     */
    public function getAvailableTypes();

    /**
     * @return array
     */
    public function getAvailableTypesForSelect();

    /**
     * @param string $key
     * @param string $value
     *
     * @return FieldType|null
     */
    public function getFieldTypeBy($key, $value);

    /**
     * @param string $class
     *
     * @return string|null
     */
    public function getTypeByClassName($class);

    /**
     * @param string $type
     *
     * @return string|null
     */
    public function getClassNameByType($type);
}
