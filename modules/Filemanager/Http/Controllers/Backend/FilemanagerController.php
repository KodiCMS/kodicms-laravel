<?php namespace KodiCMS\Filemanager\Http\Controllers\Backend;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\CMS\Assets\Core as Assets;

class FilemanagerController extends BackendController
{
	public function show()
	{
		Assets::package(array('elfinder', 'jquery-ui', 'ace'));
	}
}