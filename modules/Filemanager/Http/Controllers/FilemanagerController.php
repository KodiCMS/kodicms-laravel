<?php namespace KodiCMS\Filemanager\Http\Controllers;

use Assets;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class FilemanagerController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'filemanager::';

	public function show()
	{
		Assets::package(array('elfinder', 'jquery-ui', 'ace'));
		$this->setContent('filemanager');
	}

	public function popup()
	{
		Assets::package(array('elfinder', 'jquery-ui', 'ace'));
		$this->setContent('popup');
	}
}