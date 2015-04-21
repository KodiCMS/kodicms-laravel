<?php namespace KodiCMS\CMS\Providers;

use KodiCMS\CMS\Helpers\DatabaseConfig;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * Загрузка конфигурационных файлов из БД с заменой ключей
	 */
	public function boot()
	{
		$config = DatabaseConfig::get();

		foreach($config as $group => $data)
		{
			app('config')->set($group, array_merge(config($group, []), $data));
		}
	}

	public function register()
	{

	}
}
