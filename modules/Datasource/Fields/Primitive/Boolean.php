<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;

class Boolean extends Primitive
{
	const STYLE_RADIO = 0;
	const STYLE_CHECKBOX = 1;
	const STYLE_SELECT = 2;

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'display' => 0
		];
	}

	/**
	 * @return int
	 */
	public function getSettingLenght()
	{
		$defaultLenght = 50;

		$lenght = (int) array_get($this->settings, 'lenght', $defaultLenght);

		if ($lenght == 0)
		{
			$lenght = $defaultLenght;
		}

		return $defaultLenght;
	}

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		$table->boolean($this->getDBKey());
	}

	/**
	 * TODO: translate
	 * @return array
	 */
	public function getDisplayStyles()
	{
		return [
			static::STYLE_RADIO => 'Radio buttons',
			static::STYLE_CHECKBOX => 'Checkbox',
			static::STYLE_SELECT => 'Dropdown'
		];
	}
}