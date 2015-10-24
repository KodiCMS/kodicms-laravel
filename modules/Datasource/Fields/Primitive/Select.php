<?php namespace KodiCMS\Datasource\Fields\Primitive;

use DB;
use KodiCMS\Datasource\Fields\Primitive;

class Select extends Primitive
{

	/**
	 * @var array|null
	 */
	protected $options = null;

	/**
	 * @return array
	 */
	public function booleanSettings()
	{
		return ['custom_option', 'must_be_empty'];
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'custom_option' => false,
			'must_be_empty' => true
		];
	}

	/**
	 * @return array
	 */
	public function getOptionsList()
	{
		return DB::table('datasource_enums')
			->where('field_id', $this->getId())
			->orderBy('position')
			->lists('value', 'id')
			->all();
	}

	public function removeOptionsByIds(array $ids)
	{
		DB::table('datasource_enums')
			->where('field_id', $this->getId())
			->whereIn('id', $ids)
			->delete();
	}

	public function addOption($option)
	{
		$option = trim($option);

		if (empty($option))
		{
			return null;
		}
	}
}