<?php namespace KodiCMS\Users\Providers;

use KodiCMS\CMS\Providers\ServiceProvider;
use KodiCMS\Users\Model\User;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\Users\Observers\RoleObserver;
use KodiCMS\Users\Observers\UserObserver;
use Event;

class ModuleServiceProvider extends ServiceProvider {

	public function boot()
	{
		User::observe(new UserObserver);
		UserRole::observe(new RoleObserver);

		Event::listen('view.navbar.right.after', function() {
			echo view('users::parts.navbar');
		});

		Event::listen('view.menu', function($navigation) {
			echo view('users::parts.navigation');
		}, 999);
	}

	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'KodiCMS\Users\Services\UserCreator'
		);
	}
}