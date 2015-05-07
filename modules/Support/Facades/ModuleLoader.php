<?php namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KodiCMS\CMS\Loader\ModuleLoader
 */
class ModuleLoader extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'module.loader';
	}

}
