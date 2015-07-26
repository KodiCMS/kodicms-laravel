<?php namespace KodiCMS\ModulesLoader\Providers;

use Event;
use ModulesFileSystem;

class ConfigServiceProvider extends ServiceProvider {

	public function boot()
	{
		/**
		 * Загрузка конфигов модулей
		 */
		ModulesFileSystem::loadConfigs();
		Event::fire('config.loaded');
	}

	public function register()
	{

	}
}
