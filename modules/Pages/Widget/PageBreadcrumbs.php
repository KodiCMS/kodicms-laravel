<?php

namespace KodiCMS\Pages\Widget;

use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Widget\Decorator;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use Frontpage;

class PageBreadcrumbs extends Decorator implements WidgetCacheable
{
    use WidgetCache;

    /**
     * @var array
     */
    protected $settings = [
        'cache_tags'     => ['pages'],
        'excluded_pages' => [],
    ];

    /**
     * @var string
     */
    protected $defaultFrontendTemplate = 'pages::widgets.page_breadcrumbs.default';

    /**
     * @var string
     */
    protected $settingsTemplate = 'pages::widgets.page_breadcrumbs.settings';

    /**
     * @param array $pages
     */
    public function setSettingExcludedPages(array $pages)
    {
        $this->settings['excluded_pages'] = $pages;
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        $pageSitemap = PageSitemap::get(true);

        return compact('pageSitemap');
    }

    /**
     * @return array [[KodiCMS\CMS\Breadcrumbs\Collection] $breadcrumbs]
     */
    public function prepareData()
    {
        if (($breadcrumbs = Frontpage::getBreadcrumbs()) instanceof Breadcrumbs) {
            if (count($this->excluded_pages) > 0) {
                foreach ($this->excluded_pages as $id) {
                    $breadcrumbs->deleteBy('id', $id);
                }
            }
        }

        return compact('breadcrumbs');
    }
}
