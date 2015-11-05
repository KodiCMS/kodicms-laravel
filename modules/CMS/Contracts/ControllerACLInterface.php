<?php

namespace KodiCMS\CMS\Contracts;

use Illuminate\Http\Response;

interface ControllerACLInterface
{
    /**
     * @param string $path
     */
    public function setLoginPath($path);

    /**
     * @param string $action
     */
    public function setCurrentAction($action);

    /**
     * @param array $permissions
     *
     * @return $this
     */
    public function setPermissions(array $permissions);

    /**
     * Add the item to the permissions list if it does not already exist in the permissions.
     *
     * @param string $action
     * @param string $permission
     *
     * @return $this
     */
    public function addPermission($action, $permission);

    /**
     * @param string $action
     * @param string $permission
     *
     * @return $this
     */
    public function putPermission($action, $permission);

    /**
     * @param array $actions
     *
     * @return $this
     */
    public function setAllowedActions(array $actions);

    /**
     * @param string $action
     *
     * @return $this
     */
    public function addAllowedAction($action);

    /**
     * Проверка прав текущего пользователя.
     * @return Response
     */
    public function checkPermissions();

    /**
     * @param string|array|null $message
     * @param bool              $redirect
     *
     * @return Response
     */
    public function denyAccess($message = null, $redirect = false);
}
