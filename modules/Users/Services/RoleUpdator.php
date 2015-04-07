<?php namespace KodiCMS\Users\Services;

use KodiCMS\CMS\Contracts\ModelUpdator;
use KodiCMS\Users\Model\UserRole;
use Validator;

class RoleUpdator implements ModelUpdator
{
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param integer $id
	 * @param  array $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator($id, array $data)
	{
		$validator = Validator::make($data, [
			'name' => 'required|max:32|unique:roles,name,' . $id
		]);

		return $validator;
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param integer $id
	 * @param  array $data
	 * @return User
	 */
	public function update($id, array $data)
	{
		$role = UserRole::findOrFail($id);

		$role->update(array_only($data, [
			'name', 'description'
		]));

		if ($role->id > 2 AND isset($data['permissions'])) {
			$permissions = (array) $data['permissions'];
			$role->attachPermissions($permissions);
		}

		return $role;
	}
}
