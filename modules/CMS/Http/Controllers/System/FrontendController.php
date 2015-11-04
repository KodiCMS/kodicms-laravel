<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use Meta;

class FrontendController extends TemplateController
{
    /**
     * @var  \View  page template
     */
    public $template = 'cms::app.frontend';

    public function registerMedia()
    {
        parent::registerMedia();
        Meta::loadPackage('libraries', 'core');

        $this->includeModuleMediaFile($this->getRouterController());
        $this->includeMergedMediaFile('frontendEvents', 'js/frontendEvents');
    }

    public function after()
    {
        $this->template->with('bodyId', $this->getRouterPath())->with('theme', config('cms.theme.default'));

        parent::after();
    }
}
