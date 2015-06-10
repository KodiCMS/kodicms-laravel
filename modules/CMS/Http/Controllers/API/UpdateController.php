<?php namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\API\Exceptions\Exception;
use KodiCMS\CMS\Helpers\Updater;
use KodiCMS\API\Http\Controllers\System\Controller;

class UpdateController extends Controller
{
	public function checkRemoteFiles(Updater $updater)
	{
		$files = $updater->checkFiles();
		$this->setContent(view('cms::system.remote_files_check', compact('files')));
	}

	public function diffFiles(Updater $updater)
	{

		$path = $this->getRequiredParameter('path');

		$localFile = base_path($path);
		if (!is_file($localFile))
		{
			throw new Exception("File [{$path}] not found");
		}

		$remoteFileContent = $updater->getRemoteFileContent($path);
		$localFileContent = file_get_contents($localFile);

		$this->setContent(view('cms::system.diff_files', compact('remoteFileContent', 'localFileContent')));
	}
}