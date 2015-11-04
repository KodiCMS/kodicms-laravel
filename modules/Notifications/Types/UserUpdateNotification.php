<?php

namespace KodiCMS\Notifications\Types;

class UserUpdateNotification extends DefaultNotification
{
    /**
     * @return string
     */
    public function getIcon()
    {
        return 'users';
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return trans('users::core.notifications.update', [
            'user'    => $this->notification->getObject()->username,
            'updater' => $this->notification->sender->username,
        ]);
    }
}
