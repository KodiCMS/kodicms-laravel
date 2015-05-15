<?php namespace KodiCMS\Users;

use KodiCMS\Users\Model\User as User;

class ACL
{
	const DENY  = FALSE;
	const ALLOW = TRUE;

	const ADMIN_USER = 1;
	const ADMIN_ROLE = 'administrator';

	/**
	 * Список прав
	 * @var array
	 */
	protected static $permissions = [];

	/**
	 * Получение спсика доступных прав из конфига
	 *
	 * @return array
	 */
	public static function getPermissions()
	{
		$permissions = [];

		foreach (config('permissions', []) as $module => $actions) {
			$langKey = $module . '::' . 'permissions.title';
			if (\Lang::has($langKey)) {
				$title = trans($langKey);
			} else {
				$title = ucfirst($module);
			}

			foreach ($actions as $action) {
				$permissions[$title][$module . '::' . $action] = trans($module . '::permissions.' . $action);
			}
		}

		return $permissions;
	}

	/**
	 *
	 * @param User $user
	 * @return boolean
	 */
	public static function isAdmin($user = NULL)
	{
		if ($user === NULL) {
			$user = static::getCurrentUser();
		}

		if ($user instanceof User) {
			$user_id = $user->id;
			$roles = $user->getRoles()->lists('name');
		} else {
			$user_id = (int)$user;
			$roles = ['login'];
		}

		if ($user_id == static::ADMIN_USER OR in_array(static::ADMIN_ROLE, $roles)) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Проверка прав на доступ
	 * TODO: необходимо придумать способ проверки прав доступа к разделу
	 *
	 * @param string $action
	 * @param User $user
	 * @return boolean
	 */
	public static function check($action, User $user = NULL)
	{
		if ($user === NULL) {
			$user = static::getCurrentUser();
		}

		if (!($user instanceof User)) {
			return static::DENY;
		}

		if (empty($action)) {
			return static::ALLOW;
		}

		if (static::isAdmin($user)) {
			return static::ALLOW;
		}

		if (is_array($action)) {
			$action = strtolower(implode('.', $action));
		}

		if (!isset(static::$permissions[$user->id])) {
			static::setPermissions($user);
		}

		return isset(static::$permissions[$user->id][$action]);
	}

	/**
	 * Проверка прав доступа по массиву
	 *
	 * @param array $actions
	 * @param User $user
	 * @return boolean
	 */
	public static function checkArray(array $actions, User $user = NULL)
	{
		foreach ($actions as $action) {
			if (static::check($action, $user)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * Загрузка прав доступа для пользователя
	 *
	 * @param User $user
	 */
	protected static function setPermissions(User $user)
	{
		static::$permissions[$user->id] = array_flip($user->getAllowedPermissions());
	}

	/**
	 * @return \Illuminate\Contracts\Auth\Authenticatable|null
	 */
	protected static function getCurrentUser()
	{
		return auth()->user();
	}
}