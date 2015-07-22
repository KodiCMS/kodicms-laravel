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
	 * @return array
	 */
	public function isUseFilemanager()
	{
		return (bool) $this->getSetting('use_filemanager');
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
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->string($this->getDBKey(), $this->getSetting('length'));
	}
}