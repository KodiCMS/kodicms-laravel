<?php namespace KodiCMS\Datasource\Fields\Primitive;

use Date;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;
use KodiCMS\Datasource\Fields\Primitive;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class Timestamp extends Primitive
{
	/**
	 * @var bool
	 */
	protected $isEditable = false;

	/**
	 * @var bool
	 */
	protected $changeableDatabaseField = false;

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
	public function onGetHeadlineValue(DocumentInterface $document, $value)
	{
		return Date::format($value);
	}

	/**
	 * @param SectionHeadlineInterface $headline
	 *
	 * @return array
	 */
	public function getHeadlineParameters(SectionHeadlineInterface $headline)
	{
		$params = parent::getHeadlineParameters($headline);
		$params['class'] = 'text-right';

		return $params;
	}

	/**
	 * @return string
	 */
	public function getHeadlineType()
	{
		return 'date';
	}
}