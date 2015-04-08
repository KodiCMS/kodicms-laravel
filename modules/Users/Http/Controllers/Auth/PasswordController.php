<?php namespace KodiCMS\Users\Http\Controllers\Auth;

use KodiCMS\CMS\Http\Controllers\System\FrontendController;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Session\Store as SessionStore;


class PasswordController extends FrontendController {

	/**
	 * @var string
	 */
	public $templatePrefix = 'users::';

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
	public function __construct(Guard $auth, PasswordBroker $passwords, Request $request, Response $response, SessionStore $session)
	{
		parent::__construct($request, $response, $session);

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
		$this->setContent('auth.password');
	}
}
