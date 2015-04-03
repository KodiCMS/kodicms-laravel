<?php namespace KodiCMS\Users;

use Models\User as User;

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

		foreach (config('permissions') as $module => $actions) {
			if (isset($actions['title'])) {
				$title = $actions['title'];
			} else {
				$title = $module;
			}

			foreach ($actions as $action) {
				if (is_array($action)) {
					$permissions[$title][$module . '.' . $action['action']] = $action['description'];
				}
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
			$user = \Auth::user();
		}

		if ($user instanceof User) {
			$user_id = $user->id;
			$roles = $user->roles();
		} else {
			$user_id = (int)$user;
			$roles = ['login'];
		}

		if ($user_id == self::ADMIN_USER OR in_array(self::ADMIN_ROLE, $roles)) {
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
			$user = \Auth::user();
		}

		if (!($user instanceof User)) {
			return self::DENY;
		}

		if (empty($action)) {
			return self::ALLOW;
		}

		if (self::isAdmin($user)) {
			return self::ALLOW;
		}

		if (is_array($action)) {
			$action = strtolower(implode('.', $action));
		}

		if (!isset(self::$permissions[$user->id])) {
			self::setPermissions($user);
		}

		return isset(self::$permissions[$user->id][$action]);
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
			if (self::check($action, $user)) {
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
	protected static function _set_permissions(User $user)
	{
		self::$permissions[$user->id] = array_flip($user->permissions());
	}
}