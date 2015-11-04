<?php

namespace KodiCMS\Pages\Helpers;

use Illuminate\Support\Collection;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Widgets\Collection\WidgetCollection;

class BlockWysiwyg extends Block
{
    /**
     * @var FrontendPage
     */
    protected $page;

    /**
     * @param WidgetCollection $collection
     * @param FrontendPage     $page
     */
    public function __construct(WidgetCollection $collection, FrontendPage $page)
    {
        parent::__construct($collection);
        $this->page = $page;
    }

    /**
     * @param string $name
     * @param array  $params
     */
    public function run($name, array $params = [])
    {
        $widgets = static::getWidgetsByBlock($name, $params);
        $collection = new Collection($widgets);
        $collection->sortBy(function ($widget) {
            return $widget->getPosition();
        });

        echo view('pages::pages.wysiwyg.block_placeholder', [
            'widgets' => $collection,
            'name'    => $name,
            'page'    => $this->page,
        ])->render();
    }
}
