<?php

namespace KodiCMS\Notifications\Contracts;

use Illuminate\Contracts\Support\Arrayable;
use KodiCMS\Notifications\Model\Notification;

interface NotificationTypeInterface extends Arrayable
{
    /**
     * @return id
     */
    public function getId();

    /**
     * @return string
     */
    public function getType();

    /**
     * @return string
     */
    public function getIcon();

    /**
     * @return string
     */
    public function getColor();

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return Carbon
     */
    public function getDate();

    /**
     * @param Notification $notification
     */
    public function setObject(Notification $notification);
}
