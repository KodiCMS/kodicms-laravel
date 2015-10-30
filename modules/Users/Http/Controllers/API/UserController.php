<?php
namespace KodiCMS\Users\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Users\Model\User;

class UserController extends APIController
{

    public function getRoles()
    {
        $userId = $this->getParameter('uid');
        $this->setContent(User::findOrFail($userId)->roles()->get());
    }


    public function getUsers()
    {
        $userIds = $this->getParameter('uids');

        if ( ! empty( $userIds ) AND ! is_array($userIds)) {
            $userIds = explode(',', $userIds);
        }

        $users = User::select();

        if ( ! empty( $userIds )) {
            $users->whereIn('id', $userIds);
        }

        $this->setContent($users->get());
    }


    public function getLike()
    {
        $query = $this->getRequiredParameter('query');
        $in    = (array) $this->getParameter('in', ['username']);

        $users = User::select();

        foreach ($in as $field) {
            $users->orWhere($field, 'like', "{$query}%");
        }

        $this->setContent($users->get());
    }
}