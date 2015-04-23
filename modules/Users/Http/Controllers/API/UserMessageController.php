<?php namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Users\Model\Messages;
use KodiCMS\Users\Model\MessageUsers;

class UserMessageController extends Controller
{
	public $authRequired = TRUE;

	public function postMessage()
	{
		$parentId = (int) $this->getParameter('pid');
		$message = $this->getRequiredParameter('message');

		if($parentId > 0)
		{
			$title = 0;
			$to = MessageUsers::where('message_id', $parentId)
				->lists('user_id');
		}
		else
		{
			$title = $this->getRequiredParameter('title');
			$to = (array) $this->getRequiredParameter('to');
		}

		(new Messages())->sendMessage($title, $message, $to, $parentId, $this->currentUser->id);
	}

	public function deleteMessage()
	{

	}
}