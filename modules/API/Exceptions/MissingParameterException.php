<?php namespace KodiCMS\API\Exceptions;

use Illuminate\Validation\Validator;

class MissingParameterException extends Exception {

	protected $code = Response::ERROR_MISSING_PAPAM;

	/**
	 * @var array
	 */
	protected $rules = [];

	/**
	 * @param Validator $validator
	 */
	public function __construct(Validator $validator)
	{
		$this->rules = $validator->errors()->getMessages();
		$this->message = trans('api::core.messages.missing_params', ['field' => implode(', ', array_keys($validator->failed()))]);
	}

	/**
	 * @return array
	 */
	public function getFailedRules()
	{
		return $this->rules;
	}

	/**
	 * @return array
	 */
	public function responseArray()
	{
		$data = parent::responseArray();
		$data['failed_rules'] = $this->getFailedRules();

		return $data;
	}
}
