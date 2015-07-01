<?php namespace KodiCMS\Filemanager\Http\Controllers\API;

use KodiCMS\Filemanager\elFinder\elFinder;
use KodiCMS\Filemanager\elFinder\Connector;
use KodiCMS\API\Http\Controllers\System\Controller;

class FilemanagerController extends Controller
{

	public function before()
	{
		parent::before();

		$opts = [
			'roots' => config('filemanager', 'volumes')
		];

		return view('filemanager::open_file', [
			'response' => (new Connector(new elFinder($opts)))->run($this->request)
		]);
	}
}