<?php namespace KodiCMS\Users\Http\Controllers\Auth;

use Reflinks;
use Password;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;
use KodiCMS\Users\Reflinks\Generators\ForgotPasswordGenerator;

class PasswordController extends FrontendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

	public function boot()
	{
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

		$response = Reflinks::generateToken(new ForgotPasswordGenerator($this->request->input('email')));

		switch ($response)
		{
			case Reflinks::TOKEN_GENERATED:
				return back()->with('status', trans(Password::RESET_LINK_SENT));
			default:
				return redirect()->back()
					->withInput($this->request->only('email'))
					->withErrors(['email' => trans($response)]);
		}
	}
}
