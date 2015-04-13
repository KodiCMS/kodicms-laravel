<?php namespace KodiCMS\API\Exceptions;

use Illuminate\Database\Eloquent\MassAssignmentException;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class Response {

	const NO_ERROR = 200;
	const ERROR_MISSING_PAPAM = 110;
	const ERROR_MISSING_ASSIGMENT = 150;
	const ERROR_VALIDATION = 120;
	const ERROR_UNKNOWN = 130;
	const ERROR_TOKEN = 140;
	const ERROR_PERMISSIONS = 220;
	const ERROR_UNAUTHORIZED = 403;
	const ERROR_PAGE_NOT_FOUND = 404;

	const TYPE_ERROR = 'error';
	const TYPE_CONTENT = 'content';
	const TYPE_REDIRECT = 'redirect';

	private $debug;

	/**
	 * Массив возвращаемых значений, будет преобразован в формат JSON
	 * @var array
	 */
	public $jsonResponse = [
		'content' => NULL
	];

	public function __construct($debug = true)
	{
		$this->debug = $debug;
	}

	/**
	 * Creates the error Response associated with the given Exception.
	 *
	 * @param \Exception|FlattenException $exception An \Exception instance
	 *
	 * @return Response A Response instance
	 */
	public function createResponse($exception)
	{
		$this->jsonResponse['code'] = static::NO_ERROR;
		$this->jsonResponse['type'] = static::TYPE_ERROR;

		if($exception instanceof ValidationException)
		{
			$this->jsonResponse['code'] = static::ERROR_VALIDATION;
			$this->jsonResponse['errors'] = $exception->getErrorMessages();
			$this->jsonResponse['failed_rules'] = $exception->getFailedRules();
		}
		else if ($exception instanceof MissingParameterException)
		{
			$this->jsonResponse['code'] = static::ERROR_MISSING_PAPAM;
			$this->jsonResponse['fields'] = $exception->getMissedFields();
			$this->jsonResponse['message'] = $exception->getMessage();
		}
		else if ($exception instanceof AuthenticateException)
		{
			$this->jsonResponse['code'] = static::ERROR_UNAUTHORIZED;
			$this->jsonResponse['message'] = $exception->getMessage();
		}
		else if ($exception instanceof ModelNotFoundException)
		{
			$this->jsonResponse['code'] = static::ERROR_PAGE_NOT_FOUND;
			$this->jsonResponse['message'] = $exception->getMessage();
		}
		else if ($exception instanceof MassAssignmentException)
		{
			$this->jsonResponse['code'] = static::ERROR_MISSING_ASSIGMENT;
			$this->jsonResponse['field'] = $exception->getMessage();
		}
		else if ($exception instanceof \Exception)
		{
			$this->jsonResponse['code'] = static::ERROR_UNKNOWN;
			$this->jsonResponse['message'] = $exception->getMessage();
			if($this->debug)
			{
				$this->jsonResponse['file'] = $exception->getFile();
				$this->jsonResponse['line'] = $exception->getLine();
			}
		}

		return new JsonResponse($this->jsonResponse, 500, ['Content-Type' => 'application/json']);
	}

}
