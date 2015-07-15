<?php namespace KodiCMS\Notifications\Http\Controllers\API;

use DB;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;

class NotificationsController extends APIController
{
	public function getList()
	{
		$notifications = $this->currentUser->newNotifications()->get()->lists('type');

		$this->setContent($notifications);
	}

	public function deleteRead()
	{
		$id = $this->getRequiredParameter('id');

		DB::table('notifications_users')
			->where('notification_id', $id)
			->where('user_id',  $this->currentUser->id)
			->update(['is_read' => 1]);

		$this->setContent(true);
	}
}