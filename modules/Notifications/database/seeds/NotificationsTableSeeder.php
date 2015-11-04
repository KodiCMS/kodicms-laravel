<?php

namespace KodiCMS\Notifications\database\seeds;

use Bus;
use Illuminate\Database\Seeder;
use KodiCMS\Notifications\Jobs\NotificationSend;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Bus::dispatch(new NotificationSend([1, 2, 3], 'test'));
    }
}
