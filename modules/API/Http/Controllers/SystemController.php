<?php namespace KodiCMS\API\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use KodiCMS\API\Exceptions\Exception;
use KodiCMS\API\Exceptions\MissingParameterException;
use KodiCMS\API\Exceptions\ValidationException;
use Illuminate\View\View;
use \App\Http\Controllers\Controller as BaseController;

abstract class SystemController extends BaseController
{
	const NO_ERROR = 200;
	const ERROR_MISSING_PAPAM = 110;
	const ERROR_VALIDATION = 120;
	const ERROR_UNKNOWN = 130;
	const ERROR_TOKEN = 140;
	const ERROR_PERMISSIONS = 220;
	const ERROR_PAGE_NOT_FOUND = 404;

	/**
	 * Массив возвращаемых значений, будет преобразован в формат JSON
	 * @var array
	 */
	public $jsonResponse = [
		'content' => NULL
	];

	/**
	 *
	 * @var array
	 */
	public $privateActions = [];

	/**
	 * @var array
	 */
	public $requiredFields = [];

	/**
	 *
	 * @var Illuminate\Http\Request
	 */
	protected $_request = NULL;

	/**
	 *
	 * @var Illuminate\Http\Request
	 */
	protected $_response = NULL;

	/**
	 *
	 * @param Request $request
	 * @param Response $response
	 *
	 * return void
	 */
	public function __construct(Request $request, Response $response)
	{
		$this->_request = $request;
		$this->_response = $response;

		$this->middleware('auth', ['only' => $this->privateActions]);
	}

	/**
	 * Получение значения передаваемого параметра
	 *
	 * Если параметр указан как обязательный, то при его отсутсвии на запрос
	 * вернется ошибка
	 *
	 * @param string $key Ключ
	 * @param mixed $default Значение по умолчанию, если параметр отсутсвует
	 * @param bool $isRequired Параметр обязателен для передачи
	 * @return string
	 * @throws MissingApiParameterException
	 */
	public function getParameter($key, $default = NULL, $isRequired = FALSE)
	{
		if ($isRequired === TRUE AND !$this->_request->has($key)) {
			throw (new MissingParameterException("Missing required parameter."))->setMissedFields([$key]);
		}

		$param = $this->_request->input($key, $default);

		return $param;
	}

	/**
	 *
	 * @param string $key
	 * @return string
	 */
	public function getRequiredParameter($key)
	{
		return $this->getParameter($key, NULL, TRUE);
	}

	/**
	 *
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->jsonResponse['message'] = $message;
	}

	/**
	 *
	 * @param array $errors
	 */
	public function setErrors(array $errors)
	{
		$this->jsonResponse['errors'] = $errors;
	}

	/**
	 *
	 * @param mixed $data
	 */
	public function setContent($data)
	{
		if($data instanceof View) {
			$data = $data->render();
		}

		$this->jsonResponse['content'] = $data;
	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return array
	 */
	public function callAction($method, $parameters)
	{
		$this->jsonResponse['type'] = Exception::TYPE_CONTENT;
		$this->jsonResponse['code'] = Exception::NO_ERROR;

		$missedFields = [];
		if(isset($this->requiredFields[$method]) AND is_array($this->requiredFields[$method])) {
			foreach ($this->requiredFields[$method] as $field) {
				if(!$this->_request->has($field)) {
					$missedFields[] = $field;
				}
			}
		}

		if(count($missedFields) > 0) {
			throw (new MissingParameterException("Missing required parameter."))->setMissedFields($missedFields);
		}

		$this->before();
		$response = call_user_func_array([$this, $method], $parameters);
		$this->after();

		if($response instanceof RedirectResponse) {
			$this->jsonResponse['type'] = Exception::TYPE_REDIRECT;
			$this->jsonResponse['targetUrl'] = $response->getTargetUrl();
			$this->jsonResponse['code'] = $response->getStatusCode();
		}

		$this->_response->header('Content-Type', 'application/json');
		return $this->jsonResponse;
	}

	/**
	 * Handle calls to missing methods on the controller.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 *
	 * @throws \BadMethodCallException
	 */
	public function __call($method, $parameters)
	{
		throw new \BadMethodCallException("The requested API action [$method] does not exist.");
	}

	/***************************************
	 * Magic methods
	 ***************************************/
	public function __set($key, $value)
	{
		$this->jsonResponse[$key] = $value;
	}

	public function __get($key)
	{
		return $this->jsonResponse[$key];
	}

	public function __isset($key)
	{
		return isset($this->jsonResponse[$key]);
	}

	public function __unset($key)
	{
		unset($this->jsonResponse[$key]);
	}

	/**
	 * Throw the failed validation exception.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Illuminate\Contracts\Validation\Validator  $validator
	 * @return void
	 */
	protected function throwValidationException(Request $request, $validator)
	{
		$exception = new ValidationException();
		$exception->setValidator($validator);

		throw $exception;
	}



	/**
	 * Create the response for when a request fails validation.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  array  $errors
	 * @return \Illuminate\Http\Response
	 */
	protected function buildFailedValidationResponse(Request $request, array $errors)
	{
		if ($request->ajax())
		{
			return new JsonResponse($errors, 422);
		}

		return redirect()->to($this->getRedirectUrl())
			->withInput($request->input())
			->withErrors($errors, $this->errorBag());
	}
}
