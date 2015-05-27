<?php namespace App\Http\Controllers;

use KodiCMS\Pages\Http\Controllers\System\FrontPageController;
use KodiCMS\Pages\Widget\PageMenu;
use KodiCMS\Widgets\Widget\HTML;

class TestController extends FrontPageController
{
	public function getIndex()
	{
		$layout = $this->getLayoutFile('normal.blade');

		$menu = new PageMenu('menu');

		$html = new HTML('test');
		$html->header = 'content.blade';
		$html->setFrontendTemplate('content.blade');

		$footer = new HTML('footer');

		$this->widgetCollection->addWidget($menu, 'header', 100);
		$this->widgetCollection->addWidget($html, 'header');
		$this->widgetCollection->addWidget($footer, 'footer');

		return $this->render($layout);
	}
}