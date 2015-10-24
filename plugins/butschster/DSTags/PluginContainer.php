<?php namespace Plugins\butschster\DSTags;

use KodiCMS\Plugins\Loader\BasePluginContainer;

class PluginContainer extends BasePluginContainer
{
	public function details()
	{
		return [
			'title' => 'Datasource Tags',
			'description' => 'Tags section type for datasource',
			'author' => 'Pavel Buchnev',
			'icon' => 'tags',
			'required_cms_version' => '0.0.0'
		];
	}
}