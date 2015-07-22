<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;

class String extends Primitive
{
	/**
	 * @return array
	 */
	public function booleanSettings()
	{
		return ['use_filemanager'];
	}

	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'length' => 255
		];
	}

	/**
	 * @return int
	 */
	public function getSettingLength($defaultLength)
	{
		$length = (int) array_get($this->settings, 'length', $defaultLength);

		if (!$length)
		{
			$length = $defaultLength;
		}

		return $defaultLength;
	}

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		$table->string($this->getDBKey(), $this->getSetting('length'));
	}
}