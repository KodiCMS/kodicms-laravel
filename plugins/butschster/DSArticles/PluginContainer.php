<?php

namespace Plugins\butschster\DSArticles;

use KodiCMS\Plugins\Loader\BasePluginContainer;

class PluginContainer extends BasePluginContainer
{
    /**
     * @return array
     */
    public function details()
    {
        return [
            'title'                => 'Datasource Articles',
            'description'          => 'Articles section type for datasource',
            'author'               => 'Pavel Buchnev',
            'icon'                 => 'newspaper-o',
            'required_cms_version' => '0.0.0',
        ];
    }
}
