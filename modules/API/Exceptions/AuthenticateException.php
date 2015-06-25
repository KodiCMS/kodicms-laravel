<?php namespace KodiCMS\API\Exceptions;

use KodiCMS\API\Http\Response;

class AuthenticateException extends Exception
{

	/**
	 * @var int
	 */
	protected $code = Response::ERROR_UNAUTHORIZED;
}