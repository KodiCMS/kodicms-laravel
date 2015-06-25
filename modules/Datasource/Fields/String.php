<?php namespace KodiCMS\Datasource\Fields;

use Illuminate\Database\Schema\Blueprint;

class String extends Field
{
	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'lenght' => 255
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
		$table->string($this->getDBKey(), $this->lenght);
	}
}