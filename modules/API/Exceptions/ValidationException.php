<?php namespace KodiCMS\API\Exceptions;

use \Illuminate\Validation\Validator;

class ValidationException extends Exception {
	
	/**
	 *
	 * @var array 
	 */
	protected $_messages = [];
	
	/**
	 *
	 * @var array 
	 */
	protected $_rules = [];

	/**
	 * 
	 * @param Validator $object
	 */
	public function setValidator(Validator $object)
	{
		$this->_messages = $object->errors()->getMessages();
		$this->_rules = $object->failed();
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getFailedRules()
	{
		return $this->_rules;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function getErrorMessages()
	{
		return $this->_messages;
	}
}
