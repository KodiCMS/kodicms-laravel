<?php namespace KodiCMS\CMS;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KodiCMS\CMS\Support\ModuleLoader;

class ServiceProvider extends BaseServiceProvider {

	/**
	 * @var ModuleLoader
	 */
	protected $_moduleLoader;

	public function __construct($app)
	{
		parent::__construct($app);

		$this->_moduleLoader = new ModuleLoader(Config::get('cms.modules'));
	}

	public function boot()
	{
		$this->_moduleLoader->bootModules();
	}


	public function register()
	{
		$this->_moduleLoader->registerModules();
	}

}