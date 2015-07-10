<?php namespace KodiCMS\Cron\Providers;

use App;
use Event;
use Validator;
use KodiCMS\Cron\Model\Job;
use KodiCMS\Cron\Console\Commands\Run;
use KodiCMS\Cron\Observers\JobObserver;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->registerConsoleCommand('cron.run', Run::class);
		Event::listen('kernel.handled', function ()
		{
			if (App::installed() and config('job.agent', Job::AGENT_SYSTEM) === Job::AGENT_SYSTEM)
			{
				Job::runAll();
			}
		});

		Event::listen('view.settings.bottom', function ()
		{
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