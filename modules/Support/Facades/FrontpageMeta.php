<?php namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \KodiCMS\Pages\Helpers\Meta
 */
class FrontpageMeta extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'frontpage.meta';
	}

}
