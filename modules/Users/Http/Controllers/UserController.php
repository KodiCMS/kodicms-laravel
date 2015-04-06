<?php namespace KodiCMS\Users\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;

class UserController extends BackendController {

	public $allowedActions = array(
		'getProfile'
	);

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
		//
	}

	public function getProfile()
	{
		//
	}

	public function getEdit($id)
	{
		//
	}
}