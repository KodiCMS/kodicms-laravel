<?php namespace KodiCMS\Datasource\Fields\Primitive;

use Illuminate\Validation\Validator;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Contracts\DocumentInterface;

class DateTime extends Date
{
	/**
	 * @var string
	 */
	protected $dateFormat = 'Y-m-d H:i:s';

	/**
	 * @param Blueprint $table
	 * @return \Illuminate\Support\Fluent
	 */
	public function setDatabaseFieldType(Blueprint $table)
	{
		return $table->dateTime($this->getDBKey())->default($this->getDefaultValue());
	}

	/**
	 * @param DocumentInterface $document
	 * @param Validator $validator
	 *
	 * @return array
	 */
	public function getValidationRules(DocumentInterface $document, Validator $validator)
	{
		$rules = parent::getValidationRules($document, $validator);

		$rules[] = 'date';

		return $rules;
	}
}