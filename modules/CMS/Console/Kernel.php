<?php
namespace KodiCMS\CMS\Console;

use Illuminate\Console\Scheduling\Schedule;
use KodiCMS\CMS\Console\Commands\WysiwygListCommand;
use KodiCMS\CMS\Console\Commands\PackagesListCommand;
use KodiCMS\CMS\Console\Commands\ModulePublishCommand;
use KodiCMS\CMS\Console\Commands\ControllerMakeCommand;
use KodiCMS\CMS\Console\Commands\ModuleLocaleDiffCommand;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use KodiCMS\CMS\Console\Commands\ModuleLocalePublishCommand;
use KodiCMS\CMS\Console\Commands\GenerateScriptTranslatesCommand;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        GenerateScriptTranslatesCommand::class,
        ModuleLocalePublishCommand::class,
        ModuleLocaleDiffCommand::class,
        ControllerMakeCommand::class,
        ModulePublishCommand::class,
        PackagesListCommand::class,
        WysiwygListCommand::class,
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

    }
}