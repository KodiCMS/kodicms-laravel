<?php namespace KodiCMS\CMS\Console;

use Illuminate\Console\Scheduling\Schedule;
use KodiCMS\CMS\Console\Commands\WysiwygList;
use KodiCMS\CMS\Console\Commands\ModulesList;
use KodiCMS\CMS\Console\Commands\PackagesList;
use KodiCMS\CMS\Console\Commands\ModuleLocaleDiff;
use KodiCMS\CMS\Console\Commands\ModuleLocalePublish;
use KodiCMS\CMS\Console\Commands\ModulePublishCommand;
use KodiCMS\CMS\Console\Commands\ControllerMakeCommand;
use KodiCMS\CMS\Console\Commands\GenerateScriptTranslates;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		GenerateScriptTranslates::class,
		ModuleLocalePublish::class,
		ModuleLocaleDiff::class,
		ControllerMakeCommand::class,
		ModulePublishCommand::class,
		PackagesList::class,
		ModulesList::class,
		WysiwygList::class
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