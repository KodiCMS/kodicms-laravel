<?php
namespace KodiCMS\Users\Http\Controllers;

use ACL;
use KodiCMS\Users\Repository\UserRoleRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class RoleController extends BackendController
{

    /**
     * @param UserRoleRepository $repository
     */
    public function getIndex(UserRoleRepository $repository)
    {
        $roles = $repository->paginate();
        $this->setContent('roles.list', compact('roles'));
    }


    /**
     * @param UserRoleRepository $repository
     */
    public function getCreate(UserRoleRepository $repository)
    {
        $role = $repository->instance();
        $this->setTitle(trans($this->wrapNamespace('role.title.create')));

        $permissions = ACL::getPermissionsList();
        $this->setContent('roles.create', compact('role', 'permissions'));
    }


    /**
     * @param UserRoleRepository $repository
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(UserRoleRepository $repository)
    {
        $data = $this->request->all();
        $repository->validateOnCreate($data);
        $role = $repository->create($data);

        return $this->smartRedirect([$role])
            ->with('success', trans($this->wrapNamespace('role.messages.created'), [
                'name' => $role->name
            ]));
    }


    /**
     * @param UserRoleRepository $repository
     * @param int                $id
     */
    public function getEdit(UserRoleRepository $repository, $id)
    {
        $role = $repository->findOrFail($id);
        $this->setTitle(trans($this->wrapNamespace('role.title.edit'), [
            'name' => ucfirst($role->name),
        ]));

        $permissions         = ACL::getPermissionsList();
        $selectedPermissions = $role->permissions()->lists('action')->all();

        $users = $role->users()->with('roles')->paginate();
        $this->setContent('roles.edit', compact('role', 'permissions', 'selectedPermissions', 'users'));
    }


    /**
     * @param UserRoleRepository $repository
     * @param int                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(UserRoleRepository $repository, $id)
    {
        $data = $this->request->all();
        $repository->validateOnUpdate($id, $data);
        $role = $repository->update($id, $data);

        return $this->smartRedirect([$role])
            ->with('success', trans($this->wrapNamespace('role.messages.updated'), [
                'name' => $role->name
            ]));
    }


    /**
     * @param UserRoleRepository $repository
     * @param int                $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postDelete(UserRoleRepository $repository, $id)
    {
        $role = $repository->delete($id);

        return $this->smartRedirect()
            ->with('success', trans($this->wrapNamespace('role.messages.deleted'), [
                'name' => $role->name
            ]));
    }
}