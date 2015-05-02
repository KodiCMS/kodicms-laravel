<?php namespace KodiCMS\API\Exceptions;

class Exception extends \RuntimeException {

	/**
	 * @var int
	 */
	protected $code = Response::ERROR_UNKNOWN;

	/**
	 * @return array
	 */
	public function responseArray()
	{
		return [
			'code' => $this->getCode(),
			'type' => Response::TYPE_ERROR,
			'message' => $this->getMessage(),
		];
	}
}