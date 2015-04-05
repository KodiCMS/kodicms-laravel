<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Str;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var Request
	 */
	protected $response;

	/**
	 * @var Session
	 */
	protected $session;

	/**
	 * @var bool
	 */
	public $authRequired = FALSE;

	/**
	 * @var array
	 */
	public $allowedActions = [];


	/**
	 * @param Request $request
	 * @param Response $response
	 * return void
	 */
	public function __construct(Request $request, Response $response, SessionStore $session)
	{
		$this->request = $request;
		$this->response = $response;
		$this->session = $session;

		if($this->authRequired) {
			$this->middleware('auth', ['except' => $this->allowedActions]);
		}
	}

	/**
	 * Execute after an action executed
	 * return void
	 */
	public function after()
	{

	}

	/**
	 * Execute before an action executed
	 * return void
	 */
	public function before()
	{

	}

	/**
	 * @param string $separator
	 * @return string
	 */
	public function getRouterPath($separator = '.')
	{
		$controller = $this->getRouter()->currentRouteAction();
		$namespace = array_get($this->getRouter()->getCurrentRoute()->getAction(), 'namespace');
		$path = trim(str_replace($namespace, '', $controller), '\\');

		return str_replace(['\\', '@', '..', '.controller.'], $separator, Str::snake($path, '.'));
	}

	/**
	 * @return string
	 */
	public function getRouterController()
	{
		return last(explode('\\', get_called_class()));
	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($method, $parameters)
	{
		$this->before();

		$response = call_user_func_array([$this, $method], $parameters);

		$this->after($response);

		return $response;
	}
}
