<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use ModulesFileSystem;
use Illuminate\Http\Response;
use KodiCMS\Support\Helpers\Mime;

class VirtualMediaLinksController extends Controller
{
    public function find()
    {
        $route = $this->getRouter()->getCurrentRoute();

        $file = $route->getParameter('file');
        $ext = $route->getParameter('ext');

        if ($file = ModulesFileSystem::findFile('resources', $file, $ext)) {
            return (new Response(file_get_contents($file)))
                ->header('Content-Type', Mime::byExt($ext))
                ->header('last-modified', date('r', filemtime($file)));
        }

        abort(404);
    }
}
