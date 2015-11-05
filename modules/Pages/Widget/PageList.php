<?php

namespace KodiCMS\Pages\Widget;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Contracts\WidgetPaginator as WidgetPaginatorInterface;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Widget\Decorator;
use KodiCMS\Widgets\Traits\WidgetPaginator;
use Frontpage;
use Request;

class PageList extends Decorator implements WidgetCacheable, WidgetPaginatorInterface
{
    use WidgetCache, WidgetPaginator;

    /**
     * @var array
     */
    protected $settings = [
        'cache_tags'          => ['pages', 'page_parts'],
        'page_id'             => 1,
        'include_hidden'      => false,
        'include_user_object' => false,
    ];

    /**
     * @var string
     */
    protected $defaultFrontendTemplate = 'pages::widgets.page_list.default';

    /**
     * @var string
     */
    protected $settingsTemplate = 'pages::widgets.page_list.settings';

    /**
     * @var FrontendPage
     */
    protected $currentPage;

    public function onLoad()
    {
        $this->currentPage = FrontendPage::findById($this->getPageId());
    }

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
    public function setSettingIncludeUserObject($status)
    {
        $this->settings['include_user_object'] = (bool) $status;
    }

    /**
     * @param int $id
     */
    public function setSettingPageId($id)
    {
        $this->settings['page_id'] = (int) $id;
    }

    /**
     * @return FrontendPage
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * @return null|int
     */
    public function getPageId()
    {
        if ($this->settings['page_id'] > 0) {
            return $this->settings['page_id'];
        } elseif ($this->settings['page_id'] == 0 and (($page = Frontpage::getFacadeRoot()) instanceof FrontendPage)) {
            return $page->getId();
        }

        return;
    }

    /**
     * @return int
     */
    public function getTotalDocuments()
    {
        if (! is_null($page = $this->getCurrentPage())) {
            return $page->childrenCount($this->include_hidden);
        }

        return 0;
    }

    /**
     * @return string
     */
    public function getCacheKey()
    {
        return parent::getCacheKey().'::'.Request::path();
    }

    /**
     * @return array
     */
    public function prepareSettingsData()
    {
        $pageSitemap = PageSitemap::get(true);

        $select = [trans('pages::widgets.page_list.label.linked_page')];

        foreach ($pageSitemap->flatten() as $page) {
            $uri = ! empty($page['uri']) ? $page['uri'] : '/';
            $select[$page['id']] = $page['title'].' [ '.$uri.' ]';
        }

        return compact('select');
    }

    /**
     * @return array [[array] $pages]
     */
    public function prepareData()
    {
        if (is_null($currentPage = $this->getCurrentPage())) {
            return [];
        }

        $query = $currentPage->getChildrenQuery()->with([
                'createdBy',
                'updatedBy',
            ])->limit($this->list_size)->offset($this->list_offset);

        $pages = [];
        foreach ($query->get() as $row) {
            $page = $row->toArray();
            $page['created_by'] = $row->createdBy;
            $page['updated_by'] = $row->updatedBy;
            $pages[$row->id] = new FrontendPage((object) $page, $currentPage);
        }

        return [
            'pages' => $pages,
        ];
    }
}
