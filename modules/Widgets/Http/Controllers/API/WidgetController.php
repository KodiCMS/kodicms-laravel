<?php namespace KodiCMS\Widgets\Http\Controllers\API;

use DB;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Model\Widget;

class WidgetController extends Controller {

	public function putPlace()
	{
		$widgetId = (int) $this->getRequiredParameter('widget_id');
		$pageId = (int) $this->getRequiredParameter('page_id');

		$insert = DB::table('page_widgets')
			->insert(['page_id' => $pageId, 'widget_id' => $widgetId]);

		$widget = Widget::findOrFail($widgetId);
		$this->setContent(view('widgets::widgets.page.row', [
			'widget' => $widget->toWidget(),
			'block' => 0,
			'position' => 500,
			'page' => Page::findOrFail($pageId)
		]));

		$this->setMessage('Widget added to page');
	}
}