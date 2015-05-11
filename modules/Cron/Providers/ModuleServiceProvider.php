<?php namespace KodiCMS\Cron\Providers;

use Event;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Cron\Model\Job;
use KodiCMS\Cron\Observers\JobObserver;
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
		Event::listen('view.settings.bottom', function() {
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