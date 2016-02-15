<?php

namespace KodiCMS\CMS\Repository;

use Validator;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\CMS\Exceptions\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    protected $validationRules = [];

    /**
     * @param Model $model
     */
    public function __construct(Model $model = null)
    {
        $this->model = $model;
    }

    /**
     * @return array
     */
    public function validatorAttributeNames()
    {
        return [];
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     *
     * @return Model|null
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     *
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->model->query();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function instance(array $attributes = [])
    {
        $model = $this->model;

        return new $model($attributes);
    }

    /**
     * @param int|null $perPage
     *
     * @return mixed
     */
    public function paginate($perPage = null)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * @param array $data
     * @param null  $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return \Illuminate\Validation\Validator
     */
    public function validator(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        if (is_null($rules)) {
            $rules = $this->validationRules;
        }

        return Validator::make($data, $rules, $messages, $customAttributes);
    }

    /**
     * @param array $data
     * @param null  $rules
     * @param array $messages
     * @param array $customAttributes
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate(array $data = [], $rules = null, array $messages = [], array $customAttributes = [])
    {
        $validator = $this->validator($data, $rules, $messages, $customAttributes);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return Model
     */
    public function create(array $data = [])
    {
        return $this->model->create($data);
    }

    /**
     * @param int $id
     * @param array   $data
     *
     * @return Model
     */
    public function update($id, array $data = [])
    {
        $instance = $this->findOrFail($id);
        $instance->update($data);

        return $instance;
    }

    /**
     * @param int $id
     *
     * @return Model
     * @throws \Exception
     */
    public function delete($id)
    {
        $model = $this->findOrFail($id);
        $model->delete();

        return $model;
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     *
     * @return bool
     * @throws ValidationException
     */
    protected function _validate(\Illuminate\Validation\Validator $validator)
    {
        if (! empty($attributeNames = $this->validatorAttributeNames())) {
            $validator->setAttributeNames($attributeNames);
        }

        if ($validator->fails()) {
            throw (new ValidationException)->setValidator($validator);
        }

        return true;
    }
}
