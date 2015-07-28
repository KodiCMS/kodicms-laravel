<?php namespace KodiCMS\CMS\Http\Controllers\System;

use ModulesFileSystem;
use Illuminate\Auth\Guard;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\RedirectResponse;
use KodiCMS\Users\Http\ControllerACL;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Session\Store as SessionStore;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController
{
	use DispatchesJobs, ValidatesRequests;

	/**
	 * @var Request
	 */
	protected $request;

	/**
	 * @var string
	 */
	protected $requestType = 'GET';

	/**
	 * @var Response
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
	 * @var bool
	 */
	protected $authRequired = false;

	/**
	 * @var array
	 */
	protected $allowedActions = [];

	/**
	 * @var array
	 */
	protected $permissions = [];

	/**
	 * @var ControllersACL
	 */
	protected $acl;

	/**
	 * @var string|null
	 */
	public $moduleNamespace = null;

	public function __construct()
	{
		app()->call([$this, 'initController']);

		$this->initControllerAcl();

		// Execute method boot() on controller execute
		if (method_exists($this, 'boot'))
		{
			app()->call([$this, 'boot']);
		}

		$this->initMiddleware();
	}

	/**
	 * Execute before an action executed
	 * return void
	 */
	public function before() {}

	/**
	 * Execute after an action executed
	 * return void
	 */
	public function after() {}

	/**
	 * @param Request $request
	 * @param Response $response
	 * @param SessionStore $session
	 * @param Guard $auth
	 */
	public function initController(Request $request, Response $response, SessionStore $session, Guard $auth)
	{
		$this->request = $request;
		$this->response = $response;
		$this->session = $session;

		$this->requestType = $this->request->input('type', $this->request->method());

		$this->loadCurrentUser($auth);
	}

	public function initControllerAcl()
	{
		$this->acl = $this->getControllerAcl();

		app()->instance('acl.controller', $this->acl);

		$this->acl->setPermissions($this->permissions)
			->setAllowedActions($this->allowedActions)
			->setCurrentAction($this->getCurrentAction());
	}

	public function initMiddleware()
	{
		if ($this->authRequired)
		{
			$this->middleware('backend.auth');
		}
	}

	/**
	 * @param string $separator
	 * @return string
	 */
	public function getRouterPath($separator = '.')
	{
		if (!is_null($this->getRouter()))
		{
			$controller = $this->getRouter()->currentRouteAction();
			$namespace = array_get($this->getRouter()->getCurrentRoute()->getAction(), 'namespace');
			$path = trim(str_replace($namespace, '', $controller), '\\');

			return str_replace(['\\', '@', '..', '.controller.'], $separator, Str::snake($path, '.'));
		}

		return null;
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
		if (!is_null($this->getRouter()) AND !is_null($this->getRouter()->currentRouteAction()))
		{
			list($class, $method) = explode('@', $this->getRouter()->currentRouteAction(), 2);
		}
		else
		{
			$method = null;
		}

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
	 * @param array $parameters
	 * @param string|null $route
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function smartRedirect(array $parameters = [], $route = null)
	{
		$isContinue = !is_null($this->request->get('continue'));

		if ($route === null)
		{
			if ($isContinue)
			{
				$route = action('\\' . get_called_class() . '@getEdit', $parameters);
			}
			else
			{
				$route = action('\\' . get_called_class() . '@getIndex');
			}
		}
		else if (strpos($route, '@') !== false)
		{
			$route = action($route, $parameters);
		}
		else
		{
			$route = route($route, $parameters);
		}

		if ($isContinue AND $this->getCurrentAction() != 'postCreate')
		{
			return back();
		}

		return redirect($route);
	}

	/**
	 * @param RedirectResponse $response
	 * @throws HttpResponseException
	 */
	public function throwFailException(RedirectResponse $response)
	{
		throw new HttpResponseException($response);
	}

	/**
	 * @param Guard $auth
	 */

	/**
	 * @param Guard $auth
	 */

	protected function loadCurrentUser(Guard $auth)
	{
		if ($this->authRequired)
		{
			$this->currentUser = $auth->user();
		}
	}

	/**
	 * @return KodiCMS\Users\Http\ControllerACL
	 */
	protected function getControllerAcl()
	{
		return new ControllerACL();
	}

	/**
	 * @return string
	 */
	protected function getModuleNamespace()
	{
		if (is_null($this->moduleNamespace))
		{
			return ModulesFileSystem::getModuleNameByNamespace() . '::';
		}

		return $this->moduleNamespace;
	}

	/**
	 * @param string $string
	 * @return string
	 */
	protected function wrapNamespace($string)
	{
		if (strpos($string, '::') === false)
		{
			$string = $this->getModuleNamespace() . $string;
		}

		return $string;
	}
}
