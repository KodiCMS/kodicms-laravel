<?php

namespace KodiCMS\Users\Observers;

use DB;

/**
 * Class UserObserver.
 */
class UserObserver
{
    /**
     * @param \KodiCMS\Users\Model\User $user
     *
     * @return void
     */
    public function deleted($user)
    {
        // Удаление связанных ролей
        $user->roles()->sync([]);
    }

    /**
     * @param \KodiCMS\Users\Model\User $user
     *
     * @return void
     */
    public function authenticated($user)
    {
        // Update the number of logins
        $user->logins = DB::raw('logins + 1');

        // Set the last login date
        $user->last_login = time();

        $user->update();
    }
}
