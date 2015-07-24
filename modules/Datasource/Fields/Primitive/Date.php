<?php namespace KodiCMS\Datasource\Fields\Primitive;

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\FieldTypeDateInterface;
use KodiCMS\Datasource\Fields\Primitive;

class Date extends Primitive implements FieldTypeDateInterface
{
	/**
	 * @var string
	 */
	protected $dateFormat = 'Y-m-d';

	/**
	 * @return array
	 */
	public function booleanSettings()
	{
		return ['set_current'];
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'default_value' => '0000-00-00'
		];
	}

	/**
	 * @return array
	 */
	public function isCurrentDateByDefault()
	{
		return (bool) $this->getSetting('set_current');
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue()
	{
		if ($this->isCurrentDateByDefault())
		{
			return date($this->dateFormat);
		}

		return $this->getSetting('default_value');
	}

	/**
	 * @return string
	 */
	public function getDatabaseDefaultValue()
	{
		return $this->getSetting('default_value');
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->date($this->getDBKey())->default($this->getDatabaseDefaultValue());
	}
}