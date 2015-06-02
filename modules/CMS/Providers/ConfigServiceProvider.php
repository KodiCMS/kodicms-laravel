<?php namespace KodiCMS\CMS\Providers;

use CMS;
use Event;
use ModuleLoader;
use KodiCMS\CMS\Helpers\DatabaseConfig;

class ConfigServiceProvider extends ServiceProvider {

	public function boot()
	{
		/**
		 * Загрузка конфигов модулей
		 */
		foreach (ModuleLoader::getRegisteredModules() as $module)
		{
			$config = $module->loadConfig();
			foreach($config as $group => $data)
			{
				app('config')->set($group, $data);
			}
		}

		$databaseConfig = new DatabaseConfig;
		$this->app->instance('config.database', $databaseConfig);

		/**
		 * Загрузка конфигурационных файлов из БД с заменой ключей
		 */
		try
		{
			$config = $databaseConfig->getAll();
			foreach($config as $group => $data)
			{
				app('config')->set($group, array_merge(config($group, []), $data));
			}
		}
		catch(\PDOException $e) {} // Если таблица конфиг не существует

		Event::fire('config.loaded');
	}

	public function register()
	{

	}
}
