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
	 * @return bool
	 */
	public function created($role)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return bool
	 */
	public function updated($role)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return bool
	 */
	public function deleting($role)
	{
		return TRUE;
	}

	/**
	 * @param \KodiCMS\Users\Model\UserRole $role
	 * @return bool
	 */
	public function deleted($role)
	{
		$role->permissions()->delete();
	}

}