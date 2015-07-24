<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Model\Field;
use Illuminate\Database\Schema\Blueprint;

class Primary extends Field
{
	/**
	 * @var bool
	 */
	protected $isEditable = false;

	/**
	 * @return string
	 */
	public function getDBKey()
	{
		return $this->getKey();
	}

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->increments($this->getDBKey());
	}
}