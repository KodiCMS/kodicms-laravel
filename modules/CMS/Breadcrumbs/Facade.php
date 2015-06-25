<?php namespace KodiCMS\CMS\Breadcrumbs;

use Illuminate\Support\Facades\Facade as BaseFacade;

/**
 * Class Facade
 * TODO: Перенести в контракты? Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Breadcrumbs
 */
class Facade extends BaseFacade
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