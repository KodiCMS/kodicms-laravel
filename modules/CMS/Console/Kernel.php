<?php namespace KodiCMS\CMS\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		'KodiCMS\Installer\Console\Commands\ModuleMigrate',
		'KodiCMS\Installer\Console\Commands\ModuleSeed',
		'KodiCMS\CMS\Console\Commands\GenerateScriptTranslates',
		'KodiCMS\CMS\Console\Commands\GenerateLocalePackage'
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{

	}
}