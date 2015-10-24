<?php namespace KodiCMS\Users\Http\Middleware;

use Closure;
use KodiCMS\API\Exceptions\AuthenticateException;
use Illuminate\Contracts\Auth\Guard;

class Authenticate {

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth Services
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
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
	public function handle($request, Closure $next)
	{
		if ($this->auth->guest())
		{
			if ($request->ajax())
			{
				throw new AuthenticateException('Unauthorized.');
			}
			else
			{
				return redirect()->guest(backend_url('/auth/login'));
			}
		}

		return $next($request);
	}

}
