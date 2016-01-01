<?php

namespace KodiCMS\Notifications\Jobs;

use Event;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Notifications\Types\DefaultNotification;
use KodiCMS\Notifications\Repository\NotificationRepository;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

class NotificationSend
{
    /**
     * @var array
     */
    protected $users;

    /**
     * @var string
     */
    protected $message;

    /**
     * @var NotificationTypeInterface
     */
    protected $type;

    /**
     * @var Model
     */
    protected $object;

    /**
     * @var array
     */
    protected $parameters;

    /**
     * @param int|array                  $users
     * @param string                         $message
     * @param NotificationTypeInterface|null $type
     * @param array                          $parameters
     */
    public function __construct($users, $message = null, NotificationTypeInterface $type = null, array $parameters = [])
    {
        $this->users = (array) $users;
        $this->type = is_null($type) ? new DefaultNotification : $type;
        $this->message = $message;
        $this->parameters = $parameters;
    }

    /**
     * @param NotificationRepository $repository
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function handle(NotificationRepository $repository)
    {
        $notification = $repository->instance();

        $notification->withType($this->type);

        $notification->withMessage($this->message);

        if (! is_null($user = auth()->user())) {
            $notification->from($user);
        }

        if (! is_null($this->object)) {
            $notification->regarding($this->object);
        }

        $notification->withParameters($this->parameters);

        $notification->deliver([$this->users]);

        Event::listen('notification.send', [$notification]);

        return $notification;
    }
}
