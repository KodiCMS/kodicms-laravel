<?php namespace KodiCMS\API\Exceptions;

class AuthenticateException extends Exception
{

	/**
	 * @var int
	 */
	protected $code = Response::ERROR_UNAUTHORIZED;
}