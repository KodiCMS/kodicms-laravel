<?php

namespace KodiCMS\Notifications\Jobs;

use KodiCMS\Users\Model\UserRole;
use KodiCMS\Notifications\Contracts\NotificationTypeInterface;

class NotificationSendToRole extends NotificationSend
{
    /**
     * @param UserRole                       $role
     * @param string                         $message
     * @param NotificationTypeInterface|null $type
     * @param array                          $parameters
     */
    public function __construct(
        UserRole $role, $message = null, NotificationTypeInterface $type = null, array $parameters = []
    ) {
        parent::__construct($role->users()->lists('id')->all(), $message, $type, $parameters);
    }
}
