<?php

namespace KodiCMS\Datasource\Repository;

use KodiCMS\Datasource\Model\FieldGroup;
use KodiCMS\CMS\Repository\BaseRepository;

class FieldGroupRepository extends BaseRepository
{
    /**
     * @param FieldGroup $model
     */
    public function __construct(FieldGroup $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name'       => 'required',
            'section_id' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws FieldException
     */
    public function create(array $data = [])
    {
        return parent::create($data);
    }
}
