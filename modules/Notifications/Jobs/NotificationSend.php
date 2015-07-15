<?php namespace KodiCMS\Notifications\Jobs;

use Illuminate\Contracts\Bus\SelfHandling;
use KodiCMS\Notifications\Model\Notification;
use KodiCMS\Notifications\Types\DefaultNotification;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;
use KodiCMS\Notifications\Contracts\NotificationObjectInterface;

class NotificationSend implements SelfHandling
{
	/**
	 * @var string
	 */
	protected $message;

	/**
	 * @var NotificationTypeInterface
	 */
	protected $type;

	/**
	 * @var NotificationObjectInterface
	 */
	protected $object;

	/**
	 * @var array
	 */
	protected $users;

	/**
	 * @param integer|array $users
	 * @param string $message
	 * @param NotificationTypeInterface|null $type
	 * @param NotificationObjectInterface|null $object
	 */
	function __construct($users, $message, NotificationTypeInterface $type = null, NotificationObjectInterface $object = null)
	{
		$this->users = (array) $users;
		$this->message = $message;
		$this->type = is_null($type) ? new DefaultNotification : $type;
		$this->object = $object;
	}

	public function handle()
	{
		$notification = new Notification;

		$notification->withMessage($this->message);
		$notification->withType($this->type);

		if (!is_null($this->object))
		{
			$notification->regarding($this->object);
		}

		return $notification->deliver([$this->users]);
	}
}