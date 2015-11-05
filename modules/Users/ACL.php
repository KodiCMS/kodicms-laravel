<?php

namespace KodiCMS\Users;

use Illuminate\Contracts\Auth\Access\Gate;
use KodiCMS\Users\Model\User as User;
use Lang;

class ACL
{
    const DENY = false;
    const ALLOW = true;

    const ADMIN_USER = 1;
    const ADMIN_ROLE = 'administrator';

    /**
     * Список прав.
     * @var array
     */
    protected static $permissions = [];

    /**
     * @var array
     */
    protected $permissionsList = [];

    /**
     * @var array
     */
    protected $actions = [];

    /**
     * @var \Illuminate\Contracts\Auth\Authenticatable|null
     */
    protected $user;

    /**
     * @var Gate
     */
    protected $gate;

    /**
     * @param array $permissions
     * @param Gate  $gate
     */
    public function __construct(array $permissions = [], Gate $gate)
    {
        $this->user = auth()->user();
        $this->gate = $gate;

        foreach ($permissions as $module => $actions) {
            $langKey = $module.'::'.'permissions.title';
            if (Lang::has($langKey)) {
                $title = trans($langKey);
            } else {
                $title = ucfirst($module);
            }

            foreach ($actions as $action) {
                $this->permissionsList[$title][$action] = trans($module.'::permissions.'.$action);
                $this->actions[] = $action;
            }
        }

        foreach ($this->actions as $action) {
            $this->gate->define($action, function ($user) use ($action) {
                return $this->check($action, $user);
            });
        }
    }

    /**
     * @return array
     */
    public function getPermissionsList()
    {
        return $this->permissionsList;
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function userIsAdmin($user = null)
    {
        if ($user === null) {
            $user = $this->getUser();
        }

        if ($user instanceof User) {
            $user_id = $user->id;
            $roles = $user->getRoles()->lists('name')->all();
        } else {
            $user_id = (int) $user;
            $roles = ['login'];
        }

        if ($user_id == static::ADMIN_USER or in_array(static::ADMIN_ROLE, $roles)) {
            return true;
        }

        return false;
    }

    /**
     * Проверка прав на доступ
     *
     * @param string $action
     * @param User   $user
     *
     * @return bool
     */
    public function check($action, User $user = null)
    {
        if ($user === null) {
            $user = $this->getUser();
        }

        if (! in_array($action, $this->actions)) {
            return static::ALLOW;
        }

        if (! ($user instanceof User)) {
            return static::DENY;
        }

        if (empty($action)) {
            return static::ALLOW;
        }

        if ($this->userIsAdmin($user)) {
            return static::ALLOW;
        }

        if (is_array($action)) {
            $action = strtolower(implode('.', $action));
        }

        if (! isset(static::$permissions[$user->id])) {
            $this->setPermissions($user);
        }

        return isset(static::$permissions[$user->id][$action]);
    }

    /**
     * Проверка прав доступа по массиву.
     *
     * @param array $actions
     * @param User  $user
     *
     * @return bool
     */
    public function checkByArray(array $actions, User $user = null)
    {
        foreach ($actions as $action) {
            if ($this->check($action, $user)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Загрузка прав доступа для пользователя.
     *
     * @param User $user
     */
    protected function setPermissions(User $user)
    {
        static::$permissions[$user->id] = array_flip($user->getPermissionsByRoles());
    }
}
