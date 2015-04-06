<?php namespace KodiCMS\Users\Services;

use KodiCMS\Users\Model\User;
use Validator;

class UserCreator
{
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
			'username' => 'required|max:255|min:3|unique:users'
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
		$user = User::create(array_only($data, [
			'username', 'password', 'email', 'locale'
		]));

		if (isset($data['user_roles'])) {
			$roles = $data['user_roles'];
			if(!is_array($roles)) {
				$roles = explode(',', $roles);
			}

			$user->roles()->attach($roles);
		}

		return $user;
	}
}
