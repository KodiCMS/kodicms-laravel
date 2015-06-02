<?php namespace KodiCMS\CMS\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Validator;

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
	function __construct(Model $model = null)
	{
		$this->model = $model;
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
	 * @param integer $id
	 * @return Model|null
	 */
	public function find($id)
	{
		return $this->model->find($id);
	}

	/**
	 * @param integer $id
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
	 * @return Model
	 */
	public function instance()
	{
		return new $this->model;
	}

	/**
	 * @param int|null $perPage
	 * @return mixed
	 */
	public function paginate($perPage = null)
	{
		return $this->model->paginate($perPage);
	}

	/**
	 * @param array $data
	 * @param array|null $rules
	 * @return \Illuminate\Validation\Validator
	 */
	public function validator(array $data = [], $rules = null)
	{
		if (is_null($rules))
		{
			$rules = $this->validationRules;
		}
		return Validator::make($data, $rules);
	}

	/**
	 * @param array $data
	 * @return Model
	 */
	public function create(array $data = [])
	{
		return $this->model->create($data);
	}

	/**
	 * @param integer $id
	 * @param array $data
	 * @return Model
	 */
	public function update($id, array $data = [])
	{
		$instance = $this->find($id);
		$instance->update($data);
		return $instance;
	}

	/**
	 * @param integer $id
	 * @return bool
	 * @throws \Exception
	 */
	public function delete($id)
	{
		return $this->find($id)->delete();
	}

} 