<?php namespace KodiCMS\API\Http;

use Request;
use Symfony\Component\Yaml\Yaml;
use KodiCMS\API\Exceptions\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\MassAssignmentException;

class Response
{
	const NO_ERROR                = 200;
	const ERROR_MISSING_PAPAM     = 110;
	const ERROR_VALIDATION        = 120;
	const ERROR_UNKNOWN           = 130;
	const ERROR_TOKEN             = 140;
	const ERROR_MISSING_ASSIGMENT = 150;
	const ERROR_PERMISSIONS       = 220;
	const ERROR_UNAUTHORIZED      = 403;
	const ERROR_PAGE_NOT_FOUND    = 404;

	const TYPE_ERROR    = 'error';
	const TYPE_CONTENT  = 'content';
	const TYPE_REDIRECT = 'redirect';

	private $debug;

	/**
	 * @param bool $debug
	 */
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
	public function createExceptionResponse(\Exception $exception)
	{
		$responseData = [
			'code' => $exception->getCode(),
			'type' => static::TYPE_ERROR,
			'message' => $exception->getMessage()
		];

		if ($exception instanceof Exception or method_exists($exception, 'responseArray'))
		{
			$responseData = array_merge($responseData, $exception->responseArray());
		}
		else if ($exception instanceof ModelNotFoundException)
		{
			$responseData['code'] = static::ERROR_PAGE_NOT_FOUND;
		}
		else if ($exception instanceof MassAssignmentException)
		{
			$responseData['code'] = static::ERROR_MISSING_ASSIGMENT;
			$responseData['field'] = $exception->getMessage();
		}

		if ($this->debug)
		{
			$responseData['file'] = $exception->getFile();
			$responseData['line'] = $exception->getLine();
		}

		return $this->createResponse($responseData, 500);
	}

	/**
	 * @param array $responseData
	 * @param integer $code
	 * @return Response
	 */
	public function createResponse(array $responseData, $code = 200)
	{
		$responseType = Request::route()->parameter('type', '.json');

		switch($responseType)
		{
			case '.yaml':
				return $this->yamlResponse($responseData, $code);
			case '.xml':
				return $this->xmlResponse($responseData, $code);
			default:
				return $this->jsonResponse($responseData, $code);
		}
	}

	/**
	 * @param array $data
	 * @param integer $code
	 * @return Response
	 */
	protected function yamlResponse($data, $code)
	{
		$yaml = Yaml::dump($data);
		return new \Illuminate\Http\Response($yaml, $code, [
			'Content-Type' => 'text/x-yaml'
		]);
	}

	/**
	 * @param array $data
	 * @param integer $code
	 * @return Response
	 */
	protected function xmlResponse($data, $code)
	{
		$xml = new \SimpleXMLElement('<reponse />');

		$this->arrayToXml($data, $xml);

		return new \Illuminate\Http\Response((string) $xml->asXML(), $code, [
			'Content-Type' => 'application/xml'
		]);
	}

	protected function arrayToXml($data, &$xml)
	{
		foreach ($data as $key => $value)
		{
			if (is_numeric($key))
			{
				$key = "item_{$key}";
			}

			if (is_array($value))
			{
				$subNode = $xml->addChild($key);
				$this->arrayToXml($value, $subNode);
			}
			else
			{
				$xml->addChild($key, htmlspecialchars($value));
			}
		}
	}

	/**
	 * @param array $data
	 * @param integer $code
	 * @return JsonResponse
	 */
	protected function jsonResponse($data, $code)
	{
		return new JsonResponse($data, $code, [
			'Content-Type' => 'application/json'
		]);
	}
}
