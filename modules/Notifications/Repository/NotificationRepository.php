<?php

namespace KodiCMS\Notifications\Repository;

use KodiCMS\Users\Model\User;
use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Notifications\Model\Notification;

class NotificationRepository extends BaseRepository
{
    /**
     * @param Notification $model
     */
    public function __construct(Notification $model)
    {
        parent::__construct($model);
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function getNew(User $user)
    {
        return $user->newNotifications()->get()->lists('type');
    }

    /**
     * @param int $id
     * @param User    $user
     */
    public function markRead($id, User $user)
    {
        $this->findOrFail($id)->markRead($user->id);
    }
}
