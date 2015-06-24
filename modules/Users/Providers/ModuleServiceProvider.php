<?php namespace KodiCMS\Users\Providers;

use Event;
use KodiCMS\Users\ACL;
use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserReflink;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Users\Observers\RoleObserver;
use KodiCMS\Users\Observers\UserObserver;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Users\Reflinks\ReflinkBroker;
use KodiCMS\Users\Reflinks\ReflinkTokenRepository;
use KodiCMS\Users\Console\Commands\deleteExpiredReflinks;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->registerConsoleCommand('reflinks.clean', deleteExpiredReflinks::class);

		User::observe(new UserObserver);
		UserRole::observe(new RoleObserver);

		Event::listen('view.navbar.right.after', function ()
		{
			echo view('users::parts.navbar')->render();
		});

		Event::listen('view.menu', function ($navigation)
		{
			echo view('users::parts.navigation')->render();
		}, 999);
	}

	public function register()
	{
		$this->app->singleton('acl', function ()
		{
			return new ACL(config('permissions', []));
		});

		$this->registerReflinksBroker();
		$this->registerTokenRepository();
	}

	/**
	 * Register the reflink broker instance.
	 *
	 * @return void
	 */
	protected function registerReflinksBroker()
	{
		$this->app->singleton('reflinks', function ($app) {
			$tokens = $app['reflink.tokens'];
			return new ReflinksBroker($tokens);
		});
	}

	/**
	 * Register the token repository implementation.
	 * @return void
	 */
	protected function registerTokenRepository()
	{
		$this->app->singleton('reflink.tokens', function ($app) {

			$connection = $app['db']->connection();

			// The database token repository is an implementation of the token repository
			// interface, and is responsible for the actual storing of auth tokens and
			// their e-mail addresses. We will inject this table and hash key to it.
			$table = (new UserReflink)->getTable();

			$key = $app['config']['app.key'];

			$expire = 60;
			return new ReflinkTokenRepository($connection, $table, $key, $expire);
		});
	}
}