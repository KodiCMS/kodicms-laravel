<?php

namespace KodiCMS\Support\Helpers;

use KodiCMS\Navigation\Page;
use KodiCMS\Navigation\Section;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;

class NavigationBreadcrumbs
{
    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @var Page
     */
    private $page;

    /**
     * NavigationBreadcrumbs constructor.
     *
     * @param Breadcrumbs $breadcrumbs
     * @param Page        $page
     */
    public function __construct(Breadcrumbs $breadcrumbs, Page $page)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->page = $page;

        $this->build();
    }

    protected function build()
    {
        if (! is_null($parent = $this->page->getRootSection())) {
            $this->addBreadcrumb($parent);
        }

        if (! is_null($title = $this->page->getName())) {
            $this->breadcrumbs->add($this->page->getName(), $this->page->getUrl());
        }
    }

    /**
     * @param Section $section
     */
    protected function addBreadcrumb(Section $section)
    {
        if ($section->isRoot()) {
            return;
        }

        if (! is_null($parent = $section->getRootSection())) {
            $this->addBreadcrumb($parent);
        }
        $this->breadcrumbs->add($section->getName(), $section->getUrl());
    }
}
