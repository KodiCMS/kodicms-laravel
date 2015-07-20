<?php namespace KodiCMS\Users\Http\Controllers\Auth;

use DB;
use Illuminate\Http\Request;
use KodiCMS\Users\Model\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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
	use AuthenticatesAndRegistersUsers, ThrottlesLogins;

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

		$this->redirectPath = backend_url();
		$this->redirectAfterLogout = backend_url('/auth/login');
	}

	public function initMiddleware()
	{
		$this->middleware('backend.guest', ['except' => ['getLogout']]);
	}

	/**
	 * Show the application login form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getLogin()
	{
		if ($this->auth->check())
		{
			return redirect()->intended($this->redirectPath());
		}

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
			$this->loginUsername() => 'required', 'password' => 'required',
		]);

		// If the class is using the ThrottlesLogins trait, we can automatically throttle
		// the login attempts for this application. We'll key this by the username and
		// the IP address of the client making these requests into this application.
		$throttles = $this->isUsingThrottlesLoginsTrait();


		if ($throttles && $this->hasTooManyLoginAttempts($request)) {
			return $this->sendLockoutResponse($request);
		}

		$credentials = $this->getCredentials($request);

		if ($this->auth->attempt($credentials, $request->has('remember')))
		{
			return $this->handleUserWasAuthenticated($request, $throttles);
		}

		// If the login attempt was unsuccessful we will increment the number of attempts
		// to login and redirect the user back to the login form. Of course, when this
		// user surpasses their maximum number of attempts they will get locked out.
		if ($throttles)
		{
			$this->incrementLoginAttempts($request);
		}

		return redirect($this->loginPath())
			->withInput($request->only('email', 'remember'))
			->withErrors([
				$this->loginUsername() => $this->getFailedLoginMessage(),
			]);
	}

	/**
	 * @param Request $request
	 * @param User $user
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function authenticated(Request $request, User $user)
	{
		// Update the number of logins
		$user->logins = DB::raw('logins + 1');

		// Set the last login date
		$user->last_login = time();
		$user->save();

		return redirect()->intended($this->redirectPath());
	}

	public function checkPermissions(){}

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
