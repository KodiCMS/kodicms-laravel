<?php namespace KodiCMS\Users\Observers;

/**
 * TODO: добавить логирование событий
 *
 * Class RoleObserver
 * @package KodiCMS\Users\Observers
 */
class RoleObserver {

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return void
	 */
	public function created($role)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return void
	 */
	public function updated($role)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return void
	 */
	public function deleting($role)
	{

	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return bool
	 */
	public function deleted($role)
	{
		$role->users()->detach();
		$role->permissions()->delete();
	}

}