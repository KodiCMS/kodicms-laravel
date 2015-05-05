<?php namespace KodiCMS\CMS\Providers;

use CMS;
use KodiCMS\CMS\Helpers\DatabaseConfig;

class ConfigServiceProvider extends ServiceProvider {

	/**
	 * Загрузка конфигурационных файлов из БД с заменой ключей
	 */
	public function boot()
	{
		try
		{
			$config = DatabaseConfig::get();
		}
		catch(\PDOException $e) // Если таблица конфиг не существует
		{
			$config = [];
		}

		foreach($config as $group => $data)
		{
			app('config')->set($group, array_merge(config($group, []), $data));
		}
	}

	public function register()
	{

	}
}
