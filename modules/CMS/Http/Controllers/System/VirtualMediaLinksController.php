<?php namespace KodiCMS\CMS\Http\Controllers\System;

use KodiCMS\CMS\Helpers\File;
use Illuminate\Http\Response;
use ModuleLoader;

class VirtualMediaLinksController extends Controller
{
	public function find()
	{
		$route = $this->getRouter()->getCurrentRoute();

		// Get the file path from the request
		$file = $route->getParameter('file');
		$ext = $route->getParameter('ext');

		// Remove the extension from the filename
		if ($file = ModuleLoader::findFile('resources', $file, $ext))
		{
			// Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed

			// Set the proper headers to allow caching
			return (new Response(file_get_contents($file)))
				->header('Content-Type', File::mimeByExt($ext))
				->header('last-modified', date('r', filemtime($file)));
		}
		else
		{
			abort(404);
		}
	}
}