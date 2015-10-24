<?php namespace KodiCMS\Users\Repository;

use KodiCMS\Users\Model\UserRole;
use KodiCMS\CMS\Repository\BaseRepository;

class UserRoleRepository extends BaseRepository
{
	/**
	 * @param UserRole $model
	 */
	public function __construct(UserRole $model)
	{
		parent::__construct($model);
	}

	/**
	 * @param array $data
	 * @return bool
	 * @throws \KodiCMS\CMS\Exceptions\ValidationException
	 */
	public function validateOnCreate(array $data = [])
	{
		$validator = $this->validator($data, [
			'name' => 'required|max:32|unique:roles'
		]);

		return $this->_validate($validator);
	}

	/**
	 * @param integer $id
	 * @param array $data
	 * @return bool
	 * @throws \KodiCMS\CMS\Exceptions\ValidationException
	 */
	public function validateOnUpdate($id, array $data = [])
	{
		$validator = $this->validator($data, [
			'name' => "required|max:32|unique:roles,name,{$id}"
		]);

		$validator->sometimes('password', 'required|confirmed|min:6', function ($input)
		{
			return !empty($input->password);
		});

		return $this->_validate($validator);
	}

	/**
	 * @param array $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function create(array $data = [])
	{
		$role = parent::create(array_only($data, [
			'name', 'description'
		]));

		if (isset($data['permissions']))
		{
			$permissions = (array) $data['permissions'];
			$role->attachPermissions($permissions);
		}

		return $role;
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function update($id, array $data = [])
	{
		$role = parent::update($id, array_only($data, [
			'name', 'description'
		]));

		if ($role->id > 2)
		{
			$permissions = [];
			if (isset($data['permissions']))
			{
				$permissions = (array) $data['permissions'];
			}

			$role->attachPermissions($permissions);
		}

		return $role;
	}
}