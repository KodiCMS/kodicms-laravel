<?php namespace KodiCMS\Datasource\Repository;

use FieldManager;
use DatasourceManager;
use KodiCMS\Datasource\Model\Field;
use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Datasource\Exceptions\FieldException;

class FieldRepository extends BaseRepository
{
	/**
	 * @param Field $model
	 */
	public function __construct(Field $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws \KodiCMS\CMS\Exceptions\ValidationException
	 */
	public function validateOnCreate(array $data = [])
	{
		$validator = $this->validator($data, [
			'key' => 'required|unique:datasource_fields,key,NULL,id,ds_id,' . array_get($data, 'ds_id'),
			'type' => 'required',
			'name' => 'required'
		]);

		return $this->_validate($validator);
	}

	/**
	 * @param array $data
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
		if (is_null($type = array_get($data, 'type')))
		{
			throw new FieldException("Type not set");
		}

		if (is_null($typeObject = FieldManager::getFieldTypeBy('type', $type)))
		{
			throw new FieldException("Datasource field type {$type} not found");
		}

		$field = parent::create($data);
		FieldManager::addFieldToSectionTable($field->section, $field);

		return $field;
	}

	/**
	 * @param array $ids
	 */
	public function deleteByIds(array $ids)
	{
		$fields = $this->instance()->whereIn('id', $ids)->get();

		foreach($fields as $field)
		{
			$field->delete();
		}
	}

	/**
	 * @param integer $fieldId
	 * @param bool $status
	 */
	public function updateVisible($fieldId, $status)
	{
		$field = $this->findOrFail($fieldId);

		$field->setVisibleStatus($status);
		$field->update();
	}

	/**
	 * @return array
	 */
	public function getSectionsForSelect()
	{
		$sections = [];

		foreach (DatasourceManager::getSections() as $id => $section)
		{
			$sections[$section->type][$id] = $section->getName();
		}

		return $sections;
	}
}