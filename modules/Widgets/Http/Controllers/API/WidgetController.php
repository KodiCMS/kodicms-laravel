<?php

namespace KodiCMS\Widgets\Http\Controllers\API;

use DB;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Model\Widget;

class WidgetController extends Controller
{
    public function putPlace()
    {
        $widgetId = (int) $this->getRequiredParameter('widget_id');
        $pageId = (int) $this->getRequiredParameter('page_id');
        $block = $this->getRequiredParameter('block');

        DB::table('page_widgets')->insert([
                'page_id'   => $pageId,
                'widget_id' => $widgetId,
                'block'     => $block,
            ]);

        $widget = Widget::findOrFail($widgetId);
        $this->setContent(view('widgets::widgets.page.row', [
            'widget'   => $widget->toWidget(),
            'block'    => $block,
            'position' => 500,
            'page'     => Page::findOrFail($pageId),
        ]));

        $this->setMessage('Widget added to page');
    }

    public function postReorder()
    {
        $pageId = $this->getRequiredParameter('id');
        $data = (array) $this->getRequiredParameter('data');

        $page = Page::find($pageId);
        $widgetsData = [];

        foreach ($data as $block => $widgets) {
            foreach ($widgets as $position => $widgetId) {
                $location = [
                    'block'    => $block,
                    'position' => $position,
                ];
                $widgetsData[$widgetId] = $location;
            }
        }

        $this->request->merge([
            'widget' => $widgetsData,
        ]);

        $page->save();
        $this->setContent(true);
    }

    public function setTemplate()
    {
        $widgetId = (int) $this->getRequiredParameter('widget_id');
        $template = $this->getParameter('template');

        $widget = Widget::findOrFail($widgetId);

        $widget->update([
            'template' => $template,
        ]);

        $this->setMessage(trans('widgets::core.messages.template_updated', ['template' => $template]));
        $this->setContent(true);
    }
}
