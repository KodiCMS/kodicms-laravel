<?php namespace KodiCMS\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Breadcrumbs
 * @package KodiCMS\Support\Facades
 */
class Breadcrumbs extends Facade
{
	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'breadcrumbs';
	}
}