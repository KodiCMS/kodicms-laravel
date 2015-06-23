<?php namespace KodiCMS\Users\Http\Controllers;

use ACL;
use KodiCMS\Users\Jobs\ReflinkHandler;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;

class ReflinkController extends FrontendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'users::';

	/**
	 * @param string $code
	 */
	public function handle($code)
	{
		Bus::dispatch(new ReflinkHandler($code, $this->currentUser));
	}
}