<?php namespace KodiCMS\Users\Providers;

use Event;
use KodiCMS\Users\ACL;
use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Users\Observers\RoleObserver;
use KodiCMS\Users\Observers\UserObserver;
use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Users\Console\Commands\CleanReflinks;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		$this->registerConsoleCommand('reflinks.clean', CleanReflinks::class);

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
	}
}