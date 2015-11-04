<?php

namespace KodiCMS\Email\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\Email\Model\EmailQueue;

class QueueSendCommand extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'cms:email:queue-send';

    /**
     * @var string
     */
    protected $description = 'Send queued emails';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        EmailQueue::sendAll();
        $this->info('All done');
    }
}
