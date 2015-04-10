<?php namespace KodiCMS\Installer\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider {

	public function register()
	{
		$this->registerConsoleCommand('module.migrate', '\KodiCMS\Installer\Console\Commands\ModuleMigrate');
		$this->registerConsoleCommand('module.seed', '\KodiCMS\Installer\Console\Commands\ModuleSeed');
	}
}