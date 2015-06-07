<?php namespace KodiCMS\Plugins\Http\Controllers;

use Assets;
use PluginLoader;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class PluginController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'plugins::';

	public function getIndex()
	{
		Assets::package(['backbone']);
		$this->setContent('list');
	}
}