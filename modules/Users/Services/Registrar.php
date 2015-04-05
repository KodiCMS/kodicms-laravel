<?php namespace KodiCMS\Users\Services;

use Illuminate\Contracts\Auth\Registrar as RegistrarContract;
use Illuminate\Contracts\Mail\Mailer as MailerContract;
use Illuminate\Http\Request;
use KodiCMS\Users\Model\User;
use Validator;

class Registrar implements RegistrarContract
{

	protected $request;

	/**
	 * Create a new password broker instance.
	 *
	 * @param  \Illuminate\Contracts\Mail\Mailer $mailer
	 */
	public function __construct(Request $request, MailerContract $mailer)
	{
		$this->request = $request;
		$this->mailer = $mailer;
	}

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
		$validator = Validator::make($data, [
			'email' => 'required|email|max:255|unique:users',
			'password' => 'required|confirmed|min:6',
			'username' => 'required|max:255|min:3'
		]);

		return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return User
	 */
	public function create(array $data)
	{
		$user = User::create([
			'email' => $data['email'],
			'password' => $data['password'],
			'fullname' => $data['fullname']
		]);

		if ($status === TRUE) {
			$this->mailer->send('emails.user.register', compact('startBalance', 'user'), function ($m) use ($user, $startBalance) {
				$m->to($user->email);
			});
		}

		return $user;
	}
}
