<?php namespace KodiCMS\API\Exceptions;

use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class Response
{

	const NO_ERROR                = 200;
	const ERROR_MISSING_PAPAM     = 110;
	const ERROR_MISSING_ASSIGMENT = 150;
	const ERROR_VALIDATION        = 120;
	const ERROR_UNKNOWN           = 130;
	const ERROR_TOKEN             = 140;
	const ERROR_PERMISSIONS       = 220;
	const ERROR_UNAUTHORIZED      = 403;
	const ERROR_PAGE_NOT_FOUND    = 404;

	const TYPE_ERROR    = 'error';
	const TYPE_CONTENT  = 'content';
	const TYPE_REDIRECT = 'redirect';

	private $debug;

	public function __construct($debug = true)
	{
		$this->debug = $debug;
	}

	/**
	 * Creates the error Response associated with the given Exception.
	 *
	 * @param \Exception $exception
	 * @return Response A Response instance
	 */
	public function createResponse(\Exception $exception)
	{
		$jsonData = [
			'code' => $exception->getCode(),
			'type' => static::TYPE_ERROR,
			'message' => $exception->getMessage()
		];

		if ($exception instanceof Exception or method_exists($exception, 'responseArray'))
		{
			$jsonData = array_merge($jsonData, $exception->responseArray());
		}
		else if ($exception instanceof ModelNotFoundException)
		{
			$jsonData['code'] = static::ERROR_PAGE_NOT_FOUND;
		}
		else if ($exception instanceof MassAssignmentException)
		{
			$jsonData['code'] = static::ERROR_MISSING_ASSIGMENT;
			$jsonData['field'] = $exception->getMessage();
		}

		if ($this->debug)
		{
			$jsonData['file'] = $exception->getFile();
			$jsonData['line'] = $exception->getLine();
		}

		return new JsonResponse($jsonData, 500, [
			'Content-Type' => 'application/json'
		]);
	}

}
