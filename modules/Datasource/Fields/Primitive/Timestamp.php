<?php namespace KodiCMS\Datasource\Fields\Primitive;

use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Timestamp extends Primitive
{
	/**
	 * @var array
	 */
	protected $attributes = [
		'is_editable' => false
	];

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->timestamp($this->getDBKey());
	}

	/**
	 * @param DocumentInterface $document
	 * @param mixed $value
	 *
	 * @return mixed
	 */
	public function onGetDocumentValue(DocumentInterface $document, $value)
	{
		return '1';
	}
}