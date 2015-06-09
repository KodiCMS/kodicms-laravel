<?php namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\CMS\Helpers\Updater;
use KodiCMS\API\Http\Controllers\System\Controller;

class UpdateController extends Controller
{
	public function checkRemoteFiles()
	{
		$updater = new Updater();
		$files = $updater->checkFiles();

		$this->setContent(view('cms::system.remote_files_check', compact('files')));
	}
}