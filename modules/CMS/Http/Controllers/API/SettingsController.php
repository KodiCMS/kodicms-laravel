<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;

class SettingsController extends Controller
{
    public function post()
    {
        event('backend.settings.validate', [$this->getParameter('config', [])]);
        event('backend.settings.save', [$this->getParameter('config', [])]);

        $this->setMessage(trans('cms::system.messages.settings.saved'));
    }
}
