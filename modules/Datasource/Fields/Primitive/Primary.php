<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Model\Field;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\FieldTypeOnlySystemInterface;

class Primary extends Field implements FieldTypeOnlySystemInterface
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

	/**
	 * @return string
	 */
	public function getHeadlineType()
	{
		return 'num';
	}
}