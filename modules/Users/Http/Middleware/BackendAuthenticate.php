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
	 * @var string
	 */
	protected $loginPath;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth Services
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
		$this->loginPath = backend_url() . '/auth/login';
	}

	/**
	 * Handle an incoming request.
	 *
	 *
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next)
	{
		if (auth()->guest())
		{
			return $this->denyAccess(trans('users::core.messages.auth.unauthorized'), true);
		}

		if (!auth()->user()->hasRole('login'))
		{
			auth()->logout();

			return $this->denyAccess(trans('users::core.messages.auth.deny_access'), true);
		}

		Lang::setLocale(auth()->user()->getLocale());

		return $next($request);
	}

	/**
	 * @param string|array|null $message
	 * @param bool $redirect
	 * @return Response
	 */
	public function denyAccess($message = null, $redirect = false)
	{
		if ($redirect)
		{
			return redirect()->guest($this->loginPath)->withErrors($message);
		}

		return abort(403, $message);
	}
}
