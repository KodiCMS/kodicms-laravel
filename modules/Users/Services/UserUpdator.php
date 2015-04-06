<?php namespace KodiCMS\Users\Services;

use KodiCMS\Users\Model\User;
use Validator;

class UserUpdator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator($id, array $data)
	{
		$validator = Validator::make($data, [
			'email' => 'required|email|max:255|unique:users,email,' . $id,
			'username' => 'required|max:255|min:3|unique:users,username,' . $id
		]);

		$validator->sometimes('password', 'required|confirmed|min:6', function($input)
		{
			return !empty($input->password);
		});

		return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array $data
	 * @return User
	 */
	public function update($id, array $data)
	{
		$user = User::findOrFail($id);

		$user->update(array_only($data, [
			'username', 'password', 'email', 'locale'
		]));

		if (isset($data['user_roles'])) {
			$roles = $data['user_roles'];
			if(!is_array($roles)) {
				$roles = explode(',', $roles);
			}

			$user->roles()->sync($roles);
		}

		return $user;
	}
}
