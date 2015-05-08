<?php namespace KodiCMS\API\Exceptions;

use Illuminate\Validation\Validator;

class ValidationException extends \KodiCMS\CMS\Exceptions\ValidationException
{

	/**
	 * @var int
	 */
	protected $code = Response::ERROR_VALIDATION;

	/**
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
