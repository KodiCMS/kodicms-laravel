<?php
namespace KodiCMS\Filemanager\Http\Controllers;

use Assets;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class FilemanagerController extends BackendController
{

	public function show()
	{
		Assets::package(['elfinder', 'jquery-ui', 'ace']);
		$this->setContent('filemanager');
	}


	public function popup()
	{
		Assets::package(['elfinder', 'jquery-ui', 'ace']);
		$this->setContent('popup');
	}
}