<?php namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Auth\Guard;
use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Route;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Support\Str;
use KodiCMS\API\Exceptions\AuthenticateException;

abstract class Controller extends BaseController
{

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
	 * @var \KodiCMS\Users\Model\User;
	 */
	protected $currentUser;

	/**
	 * @var string
	 */
	protected $loginPath;

	/**
	 * @var bool
	 */
	protected $authRequired = FALSE;

	/**
	 * @var array
	 */
	protected $allowedActions = [];

	/**
	 * @var array
	 */
	protected $permissions = [];


	/**
	 * @param Request $request
	 * @param Response $response
	 * return void
	 */
	public function __construct(Request $request, Response $response, SessionStore $session, Guard $auth)
	{
		$this->request = $request;
		$this->response = $response;
		$this->session = $session;

		$this->currentUser = $auth->user();
		$this->loginPath = \CMS::backendPath() . '/auth/login';

		// Execute method boot() on controller execute
		if (method_exists($this, 'boot')) {
			app()->call([$this, 'boot']);
		}

		if ($this->authRequired) {
			$this->beforeFilter('@checkPermissions');
		}
	}

	/**
	 * Execute before an action executed
	 * return void
	 */
	public function before()
	{
	}

	/**
	 * Execute after an action executed
	 * return void
	 */
	public function after()
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
	 * @return string
	 */
	public function getCurrentAction()
	{
		list($class, $method) = explode('@', $this->getRouter()->currentRouteAction());

		return $method;
	}

	/**
	 * Execute an action on the controller.
	 *
	 * @param  string $method
	 * @param  array $parameters
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function callAction($method, $parameters)
	{
		$this->before();
		$response = call_user_func_array([$this, $method], $parameters);
		$this->after($response);

		return $response;
	}

	/**
	 * Проверка прав текущего пользователя
	 *
	 * @param Route $router
	 * @param Request $request
	 * @return Response
	 */
	public function checkPermissions(Route $router, Request $request)
	{
		if (auth()->guest()) {
			$this->session->flash('nextUrl', $this->request->getUri());

			return $this->denyAccess(trans('users::core.messages.auth.unauthorized'), TRUE);
		}

		if (!$this->currentUser->hasRole('login')) {
			auth()->logout();

			return $this->denyAccess(trans('users::core.messages.auth.deny_access'), TRUE);
		}

		if (
			!in_array($this->getCurrentAction(), $this->allowedActions)
			AND
			!acl_check(array_get($this->permissions, $this->getCurrentAction()))
		) {
			return $this->denyAccess(trans('users::core.messages.auth.no_permissions'));
		}
	}

	/**
	 * @param string|array|null $message
	 * @param bool $redirect
	 * @return Response
	 */
	public function denyAccess($message = NULL, $redirect = FALSE)
	{
		if ($this->request->ajax()) {
			throw new AuthenticateException($message);
		} elseif ($redirect) {
			return redirect()
				->guest($this->loginPath)
				->withErrors($message);
		} else {
			return abort(403, $message);
		}
	}

	/**
	 * @param array $parameters
	 * @param string|null $route
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function smartRedirect(array $parameters = [], $route = NULL)
	{
		$isContinue = !is_null($this->request->get('continue'));

		if ($route === NULL) {
			if ($isContinue) {
				$route = action('\\' . get_called_class() . '@getEdit', $parameters);
			} else {
				$route = action('\\' . get_called_class() . '@getIndex');
			}
		} else if (strpos($route, '@') !== FALSE) {
			$route = action($route, $parameters);
		} else {
			$route = route($route, $parameters);
		}

		if ($isContinue AND $this->getCurrentAction() != 'postCreate') {
			return back();
		} else {
			return redirect($route);
		}
	}

	/**
	 * @param RedirectResponse $response
	 * @throws HttpResponseException
	 */
	public function throwFailException(RedirectResponse $response)
	{
		throw new HttpResponseException($response);
	}
}
