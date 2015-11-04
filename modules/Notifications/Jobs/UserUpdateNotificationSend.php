<?php

namespace KodiCMS\Notifications\Jobs;

use KodiCMS\Users\Model\User;
use KodiCMS\Notifications\Types\UserUpdateNotification;

class UserUpdateNotificationSend extends NotificationSend
{
    /**
     * @param int|User $userId
     * @param User         $user
     */
    public function __construct($userId, User $user)
    {
        $this->object = $user;
        parent::__construct($userId, null, new UserUpdateNotification);
    }
}
