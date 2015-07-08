<?php namespace KodiCMS\Support\Facades;

use KodiCMS\CMS\Core;
use Illuminate\Support\Facades\Facade;

class CMS extends Facade {

	const VERSION 	= Core::VERSION;
	const NAME		= Core::NAME;
	const WEBSITE	= Core::WEBSITE;

	public static function getFacadeAccessor()
	{
		return Core::class;
	}
}