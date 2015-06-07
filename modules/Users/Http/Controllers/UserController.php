<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\Users\Model\UserRole;
use KodiCMS\Support\Helpers\Locale;
use KodiCMS\Users\Repository\UserRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class UserController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

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

	public function getIndex(UserRepository $repository)
	{
		$users = $repository->paginate();
		$this->setContent('users.list', compact('users'));
	}

	public function getProfile(UserRepository $repository, $id = NULL)
	{
		$user = $repository->findOrFail($id);
		$roles = $user->roles;
		$permissions = $user->getAllowedPermissions();

		$this->setTitle(trans('users::core.title.profile_alternate', [
			'name' => ucfirst($user->username)
		]));

		$this->setContent('users.profile', compact('user', 'roles', 'permissions'));
	}

	public function getCreate(UserRepository $repository)
	{
		$user = $repository->instance();

		$this->setTitle(trans('users::core.title.create'));
		$this->templateScripts['USER'] = $user;

		$availableLocales = Locale::getAvailable();
		$rolesList = UserRole::lists('name', 'id');
		$locales = $user->getAvailableLocales();

		$this->setContent('users.create', compact('user', 'availableLocales', 'rolesList', 'locales'));
	}

	public function postCreate(UserRepository $repository)
	{
		$data = $this->request->all();
		$repository->validateOnCreate($data);

		$user = $repository->create($data);

		return $this->smartRedirect([$user])
			->with('success', trans('users::core.messages.user.created', ['name' => $user->username]));
	}

	public function getEdit(UserRepository $repository, $id)
	{
		$user = $repository->findOrFail($id);
		$this->setTitle(trans('users::core.title.edit', [
			'name' => ucfirst($user->username)
		]));
		$this->templateScripts['USER'] = $user;

		$availableLocales = Locale::getAvailable();

		$rolesList = UserRole::lists('name', 'id');
		$userRoles = $user->roles()->lists('id');
		$locales = $user->getAvailableLocales();

		$this->setContent('users.edit', compact('user', 'availableLocales', 'rolesList', 'userRoles', 'locales'));
	}

	public function postEdit(UserRepository $repository, $id)
	{
		$data = $this->request->all();
		$repository->validateOnUpdate($id, $data);
		$user = $repository->update($id, $data);

		return $this->smartRedirect([$user])
			->with('success', trans('users::core.messages.user.updated', ['name' => $user->username]));
	}

	public function postDelete(UserRepository $repository, $id)
	{
		$user = $repository->delete($id);
		return $this->smartRedirect()
			->with('success', trans('users::core.messages.user.deleted', ['name' => $user->username]));
	}
}