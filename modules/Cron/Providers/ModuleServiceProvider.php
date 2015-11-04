<?php

namespace KodiCMS\Cron\Providers;

use Event;
use Validator;
use KodiCMS\Cron\Model\Job;
use KodiCMS\Support\ServiceProvider;
use KodiCMS\Cron\Observers\JobObserver;
use KodiCMS\Cron\Console\Commands\CronRunCommand;

class ModuleServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerConsoleCommand(CronRunCommand::class);

        Event::listen('kernel.handled', function () {
            if (cms_installed() and config('job.agent', Job::AGENT_SYSTEM) === Job::AGENT_SYSTEM) {
                Job::runAll();
            }
        });

        Event::listen('view.settings.bottom', function () {
            $agents = Job::agents();
            echo view('cron::cron.settings', compact('agents'));
        });
    }

    public function boot()
    {
        Job::observe(new JobObserver);
        Validator::extend('crontab', 'KodiCMS\Cron\Support\Validator@validateCrontab');
    }
}
