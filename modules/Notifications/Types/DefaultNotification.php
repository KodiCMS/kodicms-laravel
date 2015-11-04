<?php

namespace KodiCMS\Notifications\Types;

use Carbon\Carbon;
use KodiCMS\Notifications\Model\Notification;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

class DefaultNotification implements NotificationTypeInterface
{
    /**
     * @var string
     */
    protected $message;

    /**
     * @var Notification
     */
    protected $notification;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->notification->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'information';
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return 'exclamation-triangle';
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return 'info';
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->notification->message;
    }

    /**
     * @return Carbon
     */
    public function getDate()
    {
        return (string) $this->notification->sent_at;
    }

    /**
     * @param Notification $notification
     */
    public function setObject(Notification $notification)
    {
        $this->notification = $notification;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'id'      => $this->getId(),
            'type'    => $this->getType(),
            'icon'    => $this->getIcon(),
            'color'   => $this->getColor(),
            'message' => $this->getMessage(),
            'date'    => $this->getDate(),
        ];
    }
}
