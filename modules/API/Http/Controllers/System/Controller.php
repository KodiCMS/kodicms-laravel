<?php namespace KodiCMS\API\Http\Controllers\System;

use BadMethodCallException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use KodiCMS\API\Exceptions\MissingParameterException;
use KodiCMS\API\Exceptions\Response;
use KodiCMS\API\Exceptions\ValidationException;
use KodiCMS\CMS\Http\Controllers\System\Controller as BaseController;
use Validator;

abstract class Controller extends BaseController
{
	/**
	 * Массив возвращаемых значений, будет преобразован в формат JSON
	 * @var array
	 */
	public $jsonResponse = ['content' => null];

	/**
	 * @var array
	 */
	public $requiredFields = [];

	/**
	 * Получение значения передаваемого параметра
	 *
	 * Если параметр указан как обязательный, то при его отсутсвии на запрос
	 * вернется ошибка
	 *
	 * @param string $key Ключ
	 * @param mixed $default Значение по умолчанию, если параметр отсутсвует
	 * @param bool|string|array $isRequired Параметр обязателен для передачи
	 * @return string
	 * @throws MissingApiParameterException
	 */
	public function getParameter($key, $default = null, $isRequired = false)
	{
		if (!empty($isRequired))
		{
			$this->validateParameters([$key => $isRequired]);
		}

		$param = $this->request->input($key, $default);

		return $param;
	}

	/**
	 * @param string $key
	 * @param bool|string|array $rules
	 * @return string
	 * @throws MissingApiParameterException
	 */
	public function getRequiredParameter($key, $rules = true)
	{
		return $this->getParameter($key, null, $rules);
	}

	/**
	 * @param string $message
	 */
	public function setMessage($message)
	{
		$this->jsonResponse['message'] = $message;
	}

	/**
	 * @param array $errors
	 */
	public function setErrors(array $errors)
	{
		$this->jsonResponse['errors'] = $errors;
	}

	/**
	 * @param mixed $data
	 */
	public function setContent($data)
	{
		if ($data instanceof View)
		{
			$data = $data->render();
		}

		$this->jsonResponse['content'] = $data;
	}

	/**
	 * @param array $parameters
	 * @return bool
	 * @throws MissingApiParameterException
	 */
	final public function validateParameters(array $parameters)
	{
		$parameters = array_map(function ($rules)
		{
			if (is_bool($rules) AND $rules === true)
			{
				return 'required';
			}

			return $rules;

		}, $parameters);

		$validator = Validator::make($this->request->all(), $parameters);

		if ($validator->fails())
		{
			throw new MissingParameterException($validator);
		}

		return true;
	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 * @return array
	 */
	public function callAction($method, $parameters)
	{
		$this->jsonResponse['type'] = Response::TYPE_CONTENT;
		$this->jsonResponse['method'] = $this->request->method();
		$this->jsonResponse['code'] = Response::NO_ERROR;

		if (isset($this->requiredFields[$method]) AND is_array($this->requiredFields[$method]))
		{
			$this->validateParameters($this->requiredFields[$method]);
		}

		$this->before();
		$response = call_user_func_array([$this, $method], $parameters);
		$this->after();

		if ($response instanceof RedirectResponse)
		{
			$this->jsonResponse['type'] = Response::TYPE_REDIRECT;
			$this->jsonResponse['targetUrl'] = $response->getTargetUrl();
			$this->jsonResponse['code'] = $response->getStatusCode();
		}

		$this->response->header('Content-Type', 'application/json');

		return $this->jsonResponse;
	}

	/**
	 * Handle calls to missing methods on the controller.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 * @return mixed
	 *
	 * @throws BadMethodCallException
	 */
	public function __call($method, $parameters)
	{
		throw new BadMethodCallException("The requested API action [$method] does not exist.");
	}

	/***************************************
	 * Magic methods
	 ***************************************/

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value)
	{
		$this->jsonResponse[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function __get($key)
	{
		return $this->jsonResponse[$key];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function __isset($key)
	{
		return isset($this->jsonResponse[$key]);
	}

	/**
	 * @param string $key
	 */
	public function __unset($key)
	{
		unset($this->jsonResponse[$key]);
	}

	/**
	 * Throw the failed validation exception.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Illuminate\Contracts\Validation\Validator $validator
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
	 * @param  \Illuminate\Http\Request $request
	 * @param  array $errors
	 * @return \Illuminate\Http\Response
	 */
	protected function buildFailedValidationResponse(Request $request, array $errors)
	{
		if ($request->ajax())
		{
			return new JsonResponse($errors, 422);
		}

		return redirect()->to($this->getRedirectUrl())->withInput($request->input())->withErrors($errors, $this->errorBag());
	}
}
