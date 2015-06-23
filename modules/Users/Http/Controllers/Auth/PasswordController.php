<?php namespace KodiCMS\Users\Http\Controllers\Auth;

use Bus;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use KodiCMS\Users\Jobs\ReflinkForgotPassword;
use Illuminate\Foundation\Auth\ResetsPasswords;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;

class PasswordController extends FrontendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords;


	/**
	 * Create a new password controller instance.
	 *
	 * @param Guard $auth
	 * @param PasswordBroker $passwords
	 */
	public function boot(Guard $auth, PasswordBroker $passwords)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	/**
	 * Display the form to request a password reset link.
	 *
	 * @return Response
	 */
	public function getEmail()
	{
		$this->setContent('auth.password')
			->with('status', $this->session->get('status'));
	}

	public function postEmail()
	{
		$this->validate($this->request, ['email' => 'required|email']);
		Bus::dispatch(new ReflinkForgotPassword($this->request->only('email')));

		return redirect()->back()->with('status', trans($response));
	}
}
