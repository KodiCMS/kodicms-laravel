<?php

namespace KodiCMS\CMS\Http;

use KodiCMS\CMS\Contracts\ControllerACLInterface;

class ControllerACL implements ControllerACLInterface
{

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var array
     */
    protected $allowedActions = [];

    /**
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $loginPath;

    /**
     * @param string $path
     */
    public function setLoginPath($path)
    {
        $this->loginPath = $path;
    }

    /**
     * @param string $action
     */
    public function setCurrentAction($action)
    {
        $this->action = $action;
    }

    /**
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions)
    {
        foreach ($permissions as $action => $permission) {
            $this->putPermission($action, $permission);
        }

        return $this;
    }

    /**
     * Add the item to the permissions list if it does not already exist in the permissions.
     *
     * @param string $action
     * @param string $permission
     *
     * @return $this
     */
    public function addPermission($action, $permission)
    {
        if (is_null(array_get($this->permissions, $action))) {
            $this->permissions[$action] = $permission;
        }

        return $this;
    }

    /**
     * @param string $action
     * @param string $permission
     *
     * @return $this
     */
    public function putPermission($action, $permission)
    {
        $this->permissions[$action] = $permission;

        return $this;
    }

    /**
     * @param array $actions
     *
     * @return $this
     */
    public function setAllowedActions(array $actions)
    {
        foreach ($actions as $action) {
            $this->addAllowedAction($action);
        }

        return $this;
    }

    /**
     * @param string $action
     *
     * @return $this
     */
    public function addAllowedAction($action)
    {
        $this->allowedActions[] = $action;

        return $this;
    }

    /**
     * Проверка прав текущего пользователя.
     * @return Response
     */
    public function checkPermissions()
    {
        return true;
    }

    /**
     * @param string|array|null $message
     * @param bool              $redirect
     *
     * @return Response
     */
    public function denyAccess($message = null, $redirect = false)
    {
        return abort(403, $message);
    }
}