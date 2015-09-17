<?php

namespace Plugins\butschster\DemoSite;

use KodiCMS\Plugins\Loader\BasePluginContainer;

class PluginContainer extends BasePluginContainer
{

    public function details()
    {
        return [
            'title'                => 'Demo Site',
            'author'               => 'Pavel Buchnev',
            'icon'                 => 'hand-scissors-o',
            'required_cms_version' => '0.0.0'
        ];
    }
}