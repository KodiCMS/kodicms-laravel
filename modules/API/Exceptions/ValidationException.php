<?php namespace KodiCMS\API\Exceptions;

use Illuminate\Validation\Validator;

class ValidationException extends Exception
{

	/**
	 * @var int
	 */
	protected $code = Response::ERROR_VALIDATION;

	/**
	 * @var array
	 */
	protected $messages = [];

	/**
	 * @var array
	 */
	protected $rules = [];

	/**
	 *
	 * @param Validator $object
	 */
	public function setValidator(Validator $object)
	{
		$this->messages = $object->errors()->getMessages();
		$this->rules = $object->failed();
	}

	/**
	 *
	 * @return array
	 */
	public function getFailedRules()
	{
		return $this->rules;
	}

	/**
	 *
	 * @return array
	 */
	public function getErrorMessages()
	{
		return $this->messages;
	}

	/**
	 * @return array
	 */
	public function responseArray()
	{
		$data = parent::responseArray();
		$data['failed_rules'] = $this->getFailedRules();
		$data['errors'] = $this->getErrorMessages();

		return $data;
	}
}
