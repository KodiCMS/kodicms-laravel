<?php

namespace KodiCMS\Widgets\Widget;

use Request;
use KodiCMS\Widgets\Contracts\WidgetPaginator;
use KodiCMS\Widgets\Contracts\WidgetRenderable;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Widgets\Traits\WidgetRender;
use Illuminate\Pagination\LengthAwarePaginator;

class Paginator extends Decorator implements WidgetRenderable
{
    use WidgetRender;

    protected $settings = [
        'query_key'        => 'page',
        'linked_widget_id' => 0,
    ];

    /**
     * @var LengthAwarePaginator|null
     */
    protected $paginator;

    /**
     * @var string
     */
    protected $defaultFrontendTemplate = 'widgets::widgets.paginator.default';

    /**
     * @var string
     */
    protected $settingsTemplate = 'widgets::widgets.paginator.settings';

    /**
     * @param string $key
     */
    public function setSettingQueryKey($key)
    {
        $this->settings['query_key'] = (string) $key;
    }

    /**
     * @param int $id
     */
    public function setSettingLinkedWidgetId($id)
    {
        $this->settings['linked_widget_id'] = (int) $id;
    }

    public function afterLoad()
    {
        $linkedWidget = WidgetManagerDatabase::getWidgetById($this->linked_widget_id);
        $paginator = null;

        if (! is_null($linkedWidget) and ($linkedWidget instanceof WidgetPaginator)) {
            $paginator = new LengthAwarePaginator([], $linkedWidget->getTotalDocuments(), $linkedWidget->list_size);
            $paginator->setPageName($this->query_key);
            $paginator->setPath(Request::path());

            $linkedWidget->list_offset = (int) (($paginator->currentPage() - 1) * $paginator->perPage());
        }

        $this->paginator = $paginator;
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        $widgets = WidgetManagerDatabase::getAllWidgets()->filter(function ($widget) {
            return $widget instanceof WidgetPaginator;
        });

        $select = $widgets->map(function ($widget) {
            return $widget->toArray();
        })->lists('name', 'id')->all();

        return compact('widgets', 'select');
    }

    /**
     * @return array [[LengthAwarePaginator|null] $paginator]
     */
    public function prepareData()
    {
        return [
            'paginator' => $this->paginator,
        ];
    }
}
