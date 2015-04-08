<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Users\Services\RoleCreator;
use KodiCMS\Users\Services\RoleUpdator;

class RoleController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePreffix = 'users::';

	public function getIndex()
	{
		$roles = UserRole::paginate();
		$this->setContent('roles.list', compact('roles'));
	}

	public function getCreate()
	{
		$role = new UserRole;
		$this->setTitle(trans('users::role.title.create'));

		$permissions = \ACL::getPermissions();
		$this->setContent('roles.create', compact('role', 'permissions'));
	}

	public function postCreate(RoleCreator $role)
	{
		$data = $this->request->all();

		$validator = $role->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$role = $role->create($data);

		return $this->smartRedirect([$role])
			->with('success', trans('users::role.messages.created', ['name' => $role->name]));
	}

	public function getEdit($id)
	{
		$role = $this->getRole($id);
		$this->setTitle(trans('users::role.title.edit', [
			'name' => ucfirst($role->name)
		]));

		$permissions = \ACL::getPermissions();
		$selectedPermissions = $role->permissions()->lists('action');

		$this->setContent('roles.edit', compact('role', 'permissions', 'selectedPermissions'));
	}

	public function postEdit(RoleUpdator $role, $id)
	{
		$data = $this->request->all();

		$validator = $role->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$role = $role->update($id, $data);

		return $this->smartRedirect([$role])
			->with('success', trans('users::role.messages.updated', ['name' => $role->name]));
	}

	public function getDelete($id)
	{
		$role = $this->getRole($id);
		$role->delete();

		return $this->smartRedirect()
			->with('success', trans('users::role.messages.deleted', ['name' => $role->name]));
	}

	/**
	 * @param integer $id
	 * @return UserRole|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
	 */
	protected function getRole($id)
	{
		try {
			return UserRole::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			return $this->smartRedirect()->withErrors(trans('users::role.messages.not_found'));
		}
	}
}