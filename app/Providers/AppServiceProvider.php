<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		$path = storage_path().'/logs/query.log';

		\DB::listen(function($sql, $bindings, $time) use($path) {
			// Uncomment this if you want to include bindings to queries
			$sql = str_replace(array('%', '?'), array('%%', '%s'), $sql);
			$sql = vsprintf($sql, $bindings);
			$time_now = (new \DateTime)->format('Y-m-d H:i:s');;
			$log = $time_now.' | '.$sql.' | '.$time.'ms'.PHP_EOL;
			\File::append($path, $log);
		});
	}

	/**
	 * Register any application services.
	 *
	 * This service provider is a great spot to register your various container
	 * bindings with the application. As you can see, we are registering our
	 * "Registrar" implementation here. You can add your own bindings too!
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'App\Services\Registrar'
		);
	}

}
