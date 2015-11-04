<?php

namespace KodiCMS\Notifications\Http\Controllers\API;

use KodiCMS\Notifications\Repository\NotificationRepository;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;

class NotificationsController extends APIController
{
    /**
     * @param NotificationRepository $repository
     */
    public function getList(NotificationRepository $repository)
    {
        $this->setContent($repository->getNew($this->currentUser));
    }

    /**
     * @param NotificationRepository $repository
     */
    public function deleteRead(NotificationRepository $repository)
    {
        $id = $this->getRequiredParameter('id');

        $repository->markRead($id, $this->currentUser);

        $this->setContent(true);
    }
}
