<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\Users\Repository\UserRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class UserController extends BackendController
{
	/**
	 * @var array
	 */
	public $allowedActions = [
		'getProfile'
	];

	/**
	 * Execute on controller execute
	 * return void
	 */
	public function boot()
	{
		parent::boot();

		if(auth()->check())
		{
			// Разрешение пользователю править свой профиль
			$action = $this->getCurrentAction();
			if (
				in_array($action, ['getEdit', 'postEdit'])
				AND
				$this->currentUser->id == $this->getRouter()->getCurrentRoute()->getParameter('id')
			)
			{
				$this->allowedActions[] = $action;
			}
		}
	}

	/**
	 * @param UserRepository $repository
	 */
	public function getIndex(UserRepository $repository)
	{
		$users = $repository->paginate();
		$this->setContent('users.list', compact('users'));
	}

	/**
	 * @param UserRepository $repository
	 * @param null|int $id
	 */
	public function getProfile(UserRepository $repository, $id = NULL)
	{

		$user = $repository->findOrFail($id ?: $this->currentUser->id);
		$roles = $user->roles;
		$permissions = $user->getAllowedPermissions();

		$this->setTitle(trans($this->wrapNamespace('core.title.profile_alternate'), [
			'name' => ucfirst($user->username)
		]));

		$this->setContent('users.profile', compact('user', 'roles', 'permissions'));
	}

	/**
	 * @param UserRepository $repository
	 */
	public function getCreate(UserRepository $repository)
	{
		$user = $repository->instance();
		$this->setTitle(trans($this->wrapNamespace('core.title.create')));
		$this->templateScripts['USER'] = $user;

		$this->setContent('users.create', compact('user'));
	}

	/**
	 * @param UserRepository $repository
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postCreate(UserRepository $repository)
	{
		$data = $this->request->all();
		$repository->validateOnCreate($data);
		$user = $repository->create($data);

		return $this->smartRedirect([$user])
			->with('success', trans($this->wrapNamespace('core.messages.user.created'), ['name' => $user->username]));
	}

	/**
	 * @param UserRepository $repository
	 * @param int $id
	 */
	public function getEdit(UserRepository $repository, $id)
	{
		$user = $repository->findOrFail($id);
		$this->setTitle(trans($this->wrapNamespace('core.title.edit'), [
			'name' => ucfirst($user->username)
		]));
		$this->templateScripts['USER'] = $user;

		$this->setContent('users.edit', compact('user'));
	}

	/**
	 * @param UserRepository $repository
	 * @param int $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit(UserRepository $repository, $id)
	{
		$data = $this->request->all();
		$repository->validateOnUpdate($id, $data);
		$user = $repository->update($id, $data);

		return $this->smartRedirect([$user])
			->with('success', trans($this->wrapNamespace('core.messages.user.updated'), ['name' => $user->username]));
	}

	/**
	 * @param UserRepository $repository
	 * @param int $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postDelete(UserRepository $repository, $id)
	{
		$user = $repository->delete($id);
		return $this->smartRedirect()
			->with('success', trans($this->wrapNamespace('core.messages.user.deleted'), ['name' => $user->username]));
	}
}