<?php namespace KodiCMS\Cron\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Cron\Model\Job;
use Validator;

class ModuleServiceProvider extends ServiceProvider
{

	public function register()
	{
		$this->registerConsoleCommand('cron.run', 'KodiCMS\Cron\Console\Commands\Run');
		Event::listen('kernel.handled', function ()
		{
			if (config('job.agent', Job::AGENT_SYSTEM) === Job::AGENT_SYSTEM)
			{
				Job::runAll();
			}
		});
	}

	public function boot()
	{
		Validator::extend('crontab', 'KodiCMS\Cron\Support\Validator@validateCrontab');
	}

}