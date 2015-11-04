<?php

namespace KodiCMS\Notifications\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Notifications\Model\Notification;

class DeleteExpiredCommand extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'cms:notifications:delete-expired';

    /**
     * @var string
     */
    protected $description = 'Clean old expired notifications';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        (new Notification)->deleteExpired();
    }
}
