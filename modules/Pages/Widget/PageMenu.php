<?php

namespace KodiCMS\Pages\Widget;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Widget\Decorator;
use Frontpage;

class PageMenu extends Decorator implements WidgetCacheable
{
    use WidgetCache;

    /**
     * @var array
     */
    protected $settings = [
        'page_id'          => 1,
        'cache_tags'       => ['pages'],
        'page_level'       => 0,
        'excluded_pages'   => [],
        'include_children' => false,
        'include_hidden'   => false,
        'linked_widgets'   => [],
    ];

    /**
     * @var string
     */
    protected $defaultFrontendTemplate = 'pages::widgets.page_menu.default';

    /**
     * @var string
     */
    protected $settingsTemplate = 'pages::widgets.page_menu.settings';

    /**
     * @param bool $status
     */
    public function setSettingIncludeHidden($status)
    {
        $this->settings['include_hidden'] = (bool) $status;
    }

    /**
     * @param bool $status
     */
    public function setSettingIncludeChildren($status)
    {
        $this->settings['include_children'] = (bool) $status;
    }

    /**
     * @param array $pages
     */
    public function setSettingExcludedPages(array $pages)
    {
        $this->settings['excluded_pages'] = $pages;
    }

    /**
     * @param int $level
     */
    public function setSettingPageLevel($level)
    {
        $this->settings['page_level'] = (int) $level;
    }

    /**
     * @param int $id
     */
    public function setSettingPageId($id)
    {
        $this->settings['page_id'] = (int) $id;
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        $pageSitemap = PageSitemap::get(true);

        $select = [trans('pages::widgets.page_menu.label.linked_page')];

        foreach ($pageSitemap->flatten() as $page) {
            $uri = ! empty($page['uri']) ? $page['uri'] : '/';
            $select[$page['id']] = $page['title'].' [ '.$uri.' ]';
        }

        return compact('select', 'pageSitemap');
    }

    /**
     * @return int|null
     */
    public function getPageId()
    {
        if ($this->settings['page_id'] > 0) {
            return $this->settings['page_id'];
        } elseif ($this->settings['page_id'] == 0 and (($page = Frontpage::getFacadeRoot()) instanceof FrontendPage)) {
            if ($this->page_level > 0) {
                return ! is_null($parent = $page->getParent($this->page_level)) ? $parent->getId() : null;
            }

            return $page->getId();
        }

        return;
    }

    /**
     * @return array [[PageSitemap] $sitemap, [array] $pages]
     */
    public function prepareData()
    {
        $pageSitemap = PageSitemap::get($this->include_hidden);

        if (! is_null($pageId = $this->getPageId())) {
            $pageSitemap->find($pageId);
        }

        $pageSitemap->exclude($this->excluded_pages);
        //$pageSitemap->fetchWidgets($this->linked_widgets);
        $pageSitemap->children();

        return [
            'sitemap' => $pageSitemap,
            'pages'   => $pageSitemap->asArray((bool) $this->include_children),
        ];
    }
}
