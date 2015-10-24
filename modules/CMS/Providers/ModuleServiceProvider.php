<?php namespace KodiCMS\CMS\Providers;

use Blade;
use Cache;
use Config;
use Event;
use Profiler;
use ModulesFileSystem;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\Support\Cache\SqLiteTaggedStore;
use KodiCMS\Support\Cache\DatabaseTaggedStore;
use KodiCMS\ModulesLoader\Providers\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
	public function register()
	{
		Event::listen('config.loaded', function()
		{
			if ($this->app->installed())
			{
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
				catch (\PDOException $e) {}
			}
		}, 999);

		Event::listen('illuminate.query', function($sql, $bindings, $time) {
			$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
			$sql = vsprintf($sql, $bindings);

			Profiler::append('Database', $sql, $time / 1000);
		});
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

		Cache::extend('sqlite', function($app, $config)
		{
			$connectionName = array_get($config, 'connection');
			$connectionConfig = config('database.connections.' . $connectionName);

			if (!file_exists($connectionConfig['database']))
			{
				touch($connectionConfig['database']);
			}

			$connection = $this->app['db']->connection($connectionName);
			return Cache::repository(new SqLiteTaggedStore($connection, $config['schema']));
		});

		Cache::extend('database', function($app, $config)
		{
			$connection = $this->app['db']->connection(array_get($config, 'connection'));
			return Cache::repository(new DatabaseTaggedStore($connection, $config['table']));
		});
	}
}