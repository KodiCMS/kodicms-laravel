<?php

namespace KodiCMS\Support\Traits;

use KodiCMS\CMS\Contracts\ControllerACLInterface;

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
        if (! class_exists($class = '\KodiCMS\Users\Http\ControllerACL')) {
            $class = \KodiCMS\CMS\Http\ControllerACL::class;
        }

        return new $class;
    }
}
