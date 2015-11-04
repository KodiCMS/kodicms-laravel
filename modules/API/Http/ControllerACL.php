<?php

namespace KodiCMS\API\Http;

use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Exceptions\AuthenticateException;

class ControllerACL extends \KodiCMS\Users\Http\ControllerACL
{
    /**
     * @param string|array|null $message
     * @param bool              $redirect
     *
     * @return Response
     */
    public function denyAccess($message = null, $redirect = false)
    {
        if (auth()->guest()) {
            throw new AuthenticateException($message);
        }

        throw new PermissionException(array_get($this->permissions, $this->action), $message);
    }
}
