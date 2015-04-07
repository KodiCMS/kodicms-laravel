<?php namespace KodiCMS\Users\Services;

use KodiCMS\CMS\Contracts\ModelCreator;
use KodiCMS\Users\Model\UserRole;
use Validator;

class RoleCreator implements ModelCreator
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
			'name' => 'required|max:32|unique:roles'
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
		$role = UserRole::create(array_only($data, [
			'name', 'description'
		]));

		if (isset($data['permissions'])) {
			$permissions = (array) $data['permissions'];
			$role->attachPermissions($permissions);
		}

		return $role;
	}
}
