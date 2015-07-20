<?php namespace KodiCMS\Users\Http\Middleware;

use Lang;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;

class BackendAuthenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * @var ControllerACL
	 */
	protected $acl;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth Services
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
		$this->acl = app('acl.controller');

		$this->acl->setLoginPath(backend_url('auth/login'));
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		if (auth()->guest())
		{
			return $this->acl->denyAccess(trans('users::core.messages.auth.unauthorized'), true);
		}

		if (!auth()->user()->hasRole('login'))
		{
			auth()->logout();
			return $this->acl->denyAccess(trans('users::core.messages.auth.deny_access'), true);
		}

		Lang::setLocale(auth()->user()->getLocale());

		$this->acl->checkPermissions();

		return $next($request);
	}
}
