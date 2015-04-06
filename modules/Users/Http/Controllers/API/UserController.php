<?php namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Users\Model\User;

class UserController extends APIController
{
	public $authRequired = TRUE;

	public function getRoles()
	{
		$user_id = $this->getParameter('uid');
		$this->setContent(User::findOrFail($user_id)->roles()->get());
	}
}