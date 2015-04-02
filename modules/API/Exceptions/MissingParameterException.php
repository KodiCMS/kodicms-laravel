<?php namespace KodiCMS\API\Exceptions;

class MissingParameterException extends Exception {

	/**
	 * @var array
	 */
	protected $missedFields = [];

	public function setMissedFields(array $fields)
	{
		$this->missedFields = $fields;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getMissedFields()
	{
		return $this->missedFields;
	}
}
