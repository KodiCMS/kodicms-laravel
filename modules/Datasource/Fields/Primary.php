<?php namespace KodiCMS\Datasource\Fields;

use Illuminate\Database\Schema\Blueprint;

class Primary extends Field
{
	/**
	 * @return string
	 */
	public function getDBKey()
	{
		return $this->getKey();
	}

	/**
	 * @param Blueprint $table
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		$table->increments($this->getDBKey());
	}
}