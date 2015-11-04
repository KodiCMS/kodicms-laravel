<?php

namespace KodiCMS\Filemanager\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use KodiCMS\Filemanager\elFinder\elFinder;
use KodiCMS\Filemanager\elFinder\Connector;
use KodiCMS\API\Http\Controllers\System\Controller;

class FilemanagerController extends Controller
{
    public function load()
    {
        $options = [
            'roots' => config('filemanager', 'volumes'),
        ];

        $elFinder = new elFinder(app('session.store'), $options);

        return new JsonResponse(
            (new Connector($elFinder, $this->request))->run()
        );
    }
}
