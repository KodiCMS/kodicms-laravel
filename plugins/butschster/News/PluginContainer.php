<?php namespace Plugins\butschster\News;

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
			'required_cms_version' => '0.0.0',
			'settings_template' => 'butschster:news::news.settings'
		];
	}
}