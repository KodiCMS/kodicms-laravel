<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Support\Collection;

interface FieldsCollectionInterface
{
    /**
     * @param int $id
     *
     * @return FieldInterface|null
     */
    public function getById($id);

    /**
     * @param string $key
     *
     * @return FieldInterface|null
     */
    public function getByKey($key);

    /**
     * @param string $type
     *
     * @return Collection
     */
    public function getByType($type);

    /**
     * @return array
     */
    public function getIds();

    /**
     * @return array
     */
    public function getKeys();

    /**
     * @param array| string $keys
     *
     * @return array
     */
    public function getOnly($keys);

    /**
     * @return Collection
     */
    public function getFields();

    /**
     * @return array
     */
    public function getNames();

    /**
     * @return array
     */
    public function getEditable();
}
