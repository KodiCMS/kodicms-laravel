<?php namespace KodiCMS\Filemanager\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;

class FilemanagerController extends BackendController
{
	/**
	 * @var string
	 */
	public $templatePrefix = 'filemanager::';

	public function show()
	{
		Assets::package(array('elfinder', 'jquery-ui', 'ace'));

		$this->setContent('filemanager');
	}
}