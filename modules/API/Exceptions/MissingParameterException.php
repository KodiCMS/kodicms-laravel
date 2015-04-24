<?php namespace KodiCMS\API\Exceptions;

class MissingParameterException extends Exception {

	/**
	 * @var array
	 */
	protected $missedFields = [];

	/**
	 * @param array $fields
	 */
	public function __construct(array $fields)
	{
		$this->missedFields = $fields;
		$this->message = trans('api::core.messages.missing_params', ['field' => implode(', ', $fields)]);
	}

	/**
	 * @return array
	 */
	public function getMissedFields()
	{
		return $this->missedFields;
	}
}
