<?php namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Users\Model\UserRole;

class RoleController extends APIController
{
	public $authRequired = TRUE;

	public function getAll()
	{
		$this->setContent(UserRole::get());
	}
}