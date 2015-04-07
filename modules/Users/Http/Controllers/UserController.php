<?php namespace KodiCMS\Users\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Users\Model\User;
use KodiCMS\Users\Services\UserCreator;
use KodiCMS\Users\Services\UserUpdator;

class UserController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePreffix = 'users::';

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

		// Разрешение пользователю править свой профиль
		$action = $this->getCurrentAction();
		if (
			in_array($action, ['getEdit', 'postEdit'])
			AND
			$this->currentUser->id == $this->getRouter()->getCurrentRoute()->getParameter('id')
		) {
			$this->allowedActions[] = $action;
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
		$this->setContent('users.create', compact('user'));
	}

	public function postCreate(UserCreator $registrar)
	{
		$data = $this->request->all();

		$validator = $registrar->validator($data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$user = $registrar->create($data);

		return $this->redirectAfterSave([$user])
			->with('success', trans('users::core.messages.user.created', ['name' => $user->name]));
	}

	public function getEdit($id)
	{
		$user = $this->getUser($id);
		$this->setTitle(trans('users::core.title.edit', [
			'name' => ucfirst($user->username)
		]));
		$this->templateScripts['USER'] = $user;

		$this->setContent('users.edit', compact('user'));
	}

	public function postEdit(UserUpdator $registrar, $id)
	{
		$data = $this->request->all();

		$validator = $registrar->validator($id, $data);

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request, $validator
			);
		}

		$user = $registrar->update($id, $data);

		return $this->redirectAfterSave([$user])
			->with('success', trans('users::core.messages.user.updated', ['name' => $user->name]));
	}

	public function getDelete($id)
	{
		$user = $this->getUser($id);
		$user->delete();

		return redirect(route('backend.user.list'))
			->with('success', trans('users::core.messages.user.deleted', ['name' => $user->name]));
	}

	protected function getUser($id = NULL)
	{
		if (is_null($id)) {
			return $this->currentUser;
		}

		try {
			return User::findOrFail($id);
		}
		catch (ModelNotFoundException $e) {
			return redirect(route('backend.user.list'))->withErrors(trans('users::core.messages.user.not_found'));
		}
	}
}