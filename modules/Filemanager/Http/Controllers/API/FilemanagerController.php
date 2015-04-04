<?php namespace KodiCMS\Filemanager\Http\Controllers\API;

use \KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Filemanager\elFinder\Connector;
use KodiCMS\Filemanager\elFinder\elFinder;

class FilemanagerController extends APIController
{

	public function before()
	{
		parent::before();

		$opts = [
			'roots' => config('filemanager', 'volumes')
		];

		// run elFinder
		return (new Connector(new elFinder($opts)))->run($this->request);
	}

	public function load()
	{

	}
}