<?php namespace KodiCMS\CMS\Providers;

use CMS;
use Event;
use Config;
use ModulesLoader;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ConfigServiceProvider extends ServiceProvider {

	public function boot()
	{
		/**
		 * Загрузка конфигов модулей
		 */
		foreach (ModulesLoader::getRegisteredModules() as $module)
		{
			$config = $module->loadConfig();
			foreach($config as $group => $data)
			{
				Config::set($group, $data);
			}
		}

		if (CMS::isInstalled())
		{
			/**
			 * Загрузка конфигурационных файлов из БД с заменой ключей
			 */
			try
			{
				$databaseConfig = new DatabaseConfig;
				$this->app->instance('config.database', $databaseConfig);

				$config = $databaseConfig->getAll();
				foreach ($config as $group => $data)
				{
					Config::set($group, array_merge(Config::get($group, []), $data));
				}
			}
			catch (\PDOException $e) {} // Если таблица конфиг не существует
		}

		Event::fire('config.loaded');
	}

	public function register()
	{

	}
}
