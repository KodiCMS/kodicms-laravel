<?php

namespace KodiCMS\Filemanager\Http\Controllers;

use Meta;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class FilemanagerController extends BackendController
{
    public function show()
    {
        Meta::loadPackage('elfinder', 'jquery-ui', 'ace');
        $this->setContent('filemanager');
    }

    public function popup()
    {
        Meta::loadPackage(['elfinder', 'jquery-ui', 'ace']);
        $this->setContent('popup');
    }
}
