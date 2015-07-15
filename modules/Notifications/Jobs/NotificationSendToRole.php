<?php namespace KodiCMS\Notifications\Jobs;

use KodiCMS\Users\Model\UserRole;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;
use KodiCMS\Notifications\Contracts\NotificationObjectInterface;

class NotificationSendToRole extends NotificationSend
{
	/**
	 * @param UserRole $role
	 * @param string $message
	 * @param NotificationTypeInterface|null $type
	 * @param NotificationObjectInterface|null $object
	 */
	function __construct(UserRole $role, $message, NotificationTypeInterface $type = null, NotificationObjectInterface $object = null)
	{
		parent::__construct($role->users()->lists('id')->all(), $message, $type, $object);
	}
}