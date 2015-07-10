<?php namespace KodiCMS\CMS\Providers;

use Event;
use Blade;
use Config;
use ModulesFileSystem;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		Event::listen('config.loaded', function()
		{
			if ($this->app->installed())
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
		}, 999);
	}

	public function boot()
	{
		Blade::directive('event', function($expression)
		{
			return "<?php event{$expression}; ?>";
		});

		$this->app->shutdown(function()
		{
			ModulesFileSystem::cacheFoundFiles();
		});
	}
}