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
			'style' => static::STYLE_CHECKBOX
		];
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->boolean($this->getDBKey());
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