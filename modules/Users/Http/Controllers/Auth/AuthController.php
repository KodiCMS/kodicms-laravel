<?php namespace KodiCMS\Users\Http\Controllers\Auth;

use KodiCMS\CMS\Http\Controllers\System\FrontendController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use DB;

class AuthController extends FrontendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/
	use AuthenticatesAndRegistersUsers;

	/**
	 * @var string
	 */
	protected $redirectPath = '/';

	/**
	 * @var string
	 */
	protected $redirectAfterLogout = '/';

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param Guard $auth
	 */
	public function boot(Guard $auth)
	{
		$this->auth = $auth;
		$this->redirectAfterLogout = \CMS::backendPath() . '/auth/login';

		$this->beforeFilter('@checkPermissions', ['except' => 'getLogout']);
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin()
	{
		$this->setContent('auth.login');
	}

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function postLogin(Request $request)
	{
		$this->validate($request, [
			'email' => 'required|email', 'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			// Update the number of logins
			$this->auth->user()->logins = DB::raw('logins + 1');

			// Set the last login date
			$this->auth->user()->last_login = time();
			$this->auth->user()->save();

			return redirect()->intended($this->redirectPath());
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				'email' => $this->getFailedLoginMessage(),
			]);
	}

	/**
	 * @param Route $router
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
	public function checkPermissions(Route $router, Request $request)
	{
		if ($this->auth->check() AND $this->currentUser->hasRole('login')) {
			return redirect($this->redirectPath);
		}
	}

	/**
	 * Get the failed login message.
	 *
	 * @return string
	 */
	protected function getFailedLoginMessage()
	{
		return trans('users::core.messages.auth.user_not_found');
	}
}
