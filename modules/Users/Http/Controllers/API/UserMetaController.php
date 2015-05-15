<?php namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Users\Model\UserMeta;

class UserMetaController extends APIController
{
	public $authRequired = TRUE;

	public function getData()
	{
		$key = $this->getRequiredParameter('key');
		$user_id = $this->getParameter('uid');

		$this->setContent(UserMeta::get($key, [], $user_id));
	}

	public function postData()
	{
		$key = $this->getRequiredParameter('key');
		$value = $this->getParameter('value', null);
		$user_id = $this->getParameter('uid');

		$this->setContent(UserMeta::set($key, $value, $user_id));
	}

	public function deleteData()
	{
		$key = $this->getRequiredParameter('key');
		$user_id = $this->getParameter('uid');

		$this->setContent(UserMeta::delete($key, $user_id));
	}
}