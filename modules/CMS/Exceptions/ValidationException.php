<?php namespace KodiCMS\CMS\Exceptions;

use Illuminate\Validation\Validator;

class ValidationException extends Exception
{
	/**
	 * @var array
	 */
	protected $messages = [];

	/**
	 * @var array
	 */
	protected $rules = [];

	/**
	 * @param Validator $object
	 * @return $this
	 */
	public function setValidator(Validator $object)
	{
		$this->messages = $object->errors()->getMessages();
		$this->rules = $object->failed();

		return $this;
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
}
