<?php namespace Plugins\News;

use KodiCMS\Plugins\Loader\BasePluginContainer;

class PluginContainer extends BasePluginContainer
{
	public function details()
	{
		return [
			'title' => 'News',
			'description' => 'News section',
			'author' => 'Pavel Buchnev',
			'icon' => 'newspaper-o',
			'required_cms_version' => '12.0.0'
		];
	}
}