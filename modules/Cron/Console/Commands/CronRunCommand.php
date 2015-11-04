<?php

namespace KodiCMS\Cron\Console\Commands;

use KodiCMS\Cron\Model\Job;
use Illuminate\Console\Command;

/**
 * TODO: переделать на sheduler.
 *
 * Class CronRunCommand
 */
class CronRunCommand extends Command
{
    /**
     * The console command name.
     */
    protected $name = 'cms:cron:run';

    /**
     * @var string
     */
    protected $description = 'Run all cron tasks';

    /**
     * Execute the console command.
     */
    public function fire()
    {
        Job::runAll();
        $this->info('All done');
    }
}
