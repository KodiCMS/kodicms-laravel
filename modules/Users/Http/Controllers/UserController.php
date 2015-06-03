<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\Users\Model\User;
use KodiCMS\Support\Helpers\Locale;
use KodiCMS\Users\Services\UserCreator;
use KodiCMS\Users\Services\UserUpdator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

	public function getIndex()
	{
		$users = User::with('roles')->paginate();

		$this->setContent('users.list', compact('users'));
	}

	public function getProfile($id = NULL)
	{
		$user = $this->getUser($id);
		$roles = $user->roles;
		$permissions = $user->getAllowedPermissions();

		$this->setTitle(trans('users::core.title.profile_alternate', [
			'name' => ucfirst($user->username)
		]));

		$this->setContent('users.profile', compact('user', 'roles', 'permissions'));
	}

	public function getCreate()
	{
		$user = new User;
		$this->setTitle(trans('users::core.title.create'));
		$this->templateScripts['USER'] = $user;

		$availableLocales = Locale::getAvailable();

		$this->setContent('users.create', compact('user', 'availableLocales'));
	}

	public function postCreate(UserCreator $user)
	{
		$data = $this->request->all();

		$validator = $user->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$user = $user->create($data);

		return $this->smartRedirect([$user])
			->with('success', trans('users::core.messages.user.created', ['name' => $user->username]));
	}

	public function getEdit($id)
	{
		$user = $this->getUser($id);
		$this->setTitle(trans('users::core.title.edit', [
			'name' => ucfirst($user->username)
		]));
		$this->templateScripts['USER'] = $user;

		$availableLocales = Locale::getAvailable();

		$this->setContent('users.edit', compact('user', 'availableLocales'));
	}

	public function postEdit(UserUpdator $user, $id)
	{
		$data = $this->request->all();

		$validator = $user->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$user = $user->update($id, $data);

		return $this->smartRedirect([$user])
			->with('success', trans('users::core.messages.user.updated', ['name' => $user->username]));
	}

	public function getDelete($id)
	{
		$user = $this->getUser($id);
		$user->delete();

		return $this->smartRedirect()
			->with('success', trans('users::core.messages.user.deleted', ['name' => $user->username]));
	}

	/**
	 * @param integer|null $id
	 * @return User
	 * @throws HttpResponseException
	 */
	protected function getUser($id = NULL)
	{
		if (is_null($id)) {
			return $this->currentUser;
		}

		try {
			return User::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			$this->throwFailException($this->smartRedirect()->withErrors(trans('users::core.messages.user.not_found')));
		}
	}
}