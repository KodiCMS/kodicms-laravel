<?php namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Users\Model\Role;

class RoleController extends APIController
{
	public $authRequired = TRUE;

	public function getAll()
	{
		$this->setContent(Role::get());
	}
}