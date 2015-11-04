<?php

namespace KodiCMS\Support\Traits;

use KodiCMS\CMS\Contracts\ControllerACLInterface;
use KodiCMS\Users\Http\ControllerACL as UserControllerACL;

trait ControllerACL
{
    /**
     * @var bool
     */
    protected $authRequired = false;

    /**
     * @var array
     */
    protected $allowedActions = [];

    /**
     * @var array
     */
    protected $permissions = [];

    /**
     * @var \KodiCMS\Users\Model\User;
     */
    protected $currentUser;

    /**
     * @var ControllerACLInterface
     */
    protected $acl;

    public function initControllerAcl()
    {
        $this->acl = $this->getControllerAcl();

        app()->instance('acl.controller', $this->acl);

        $this->acl
            ->setPermissions($this->permissions)
            ->setAllowedActions($this->allowedActions)
            ->setCurrentAction($this->getCurrentAction());
    }

    /**
     * @return ControllerACLInterface
     */
    protected function getControllerAcl()
    {
        return new UserControllerACL;
    }
}
