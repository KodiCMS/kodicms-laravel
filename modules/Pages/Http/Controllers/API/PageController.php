<?php

namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\Pages\Repository\PageRepository;
use KodiCMS\Users\Model\UserMeta;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;

class PageController extends APIController
{
    /**
     * @param PageRepository $repository
     */
    public function getChildren(PageRepository $repository)
    {
        $parentId = (int) $this->getRequiredParameter('parent_id');
        $level = (int) $this->getParameter('level');

        $this->setContent($this->_children($repository, $parentId, $level));
    }

    /**
     * @param PageRepository $repository
     * @param int        $parentId
     * @param int        $level
     *
     * @return null|string
     */
    protected function _children(PageRepository $repository, $parentId, $level)
    {
        $expandedRows = UserMeta::get('expanded_pages', []);

        $page = $repository->find($parentId);

        if (is_null($page)) {
            return;
        }

        $children = $repository->getChildrenByPageId($parentId);

        foreach ($children as $id => $child) {
            $children[$id]->hasChildren = $child->hasChildren();
            $children[$id]->isExpanded = in_array($child->id, $expandedRows);

            if ($children[$id]->isExpanded === true) {
                $children[$id]->childrenRows = $this->_children($repository, $child->id, $level + 1);
            }
        }

        return view('pages::pages.children', [
            'children' => $children,
            'level'     => $level + 1,
        ])->render();
    }

    /**
     * @param PageRepository $repository
     */
    public function getReorder(PageRepository $repository)
    {
        $pages = $repository->getSitemap(true)->asArray();

        $this->setContent(view('pages::pages.reorder', [
            'pages' => $pages,
        ]));
    }

    /**
     * @param PageRepository $repository
     */
    public function postReorder(PageRepository $repository)
    {
        $pages = $this->getRequiredParameter('pids', []);

        if (empty($pages)) {
            return;
        }

        $this->setContent($repository->reorder($pages));
    }

    /**
     * @param PageRepository $repository
     */
    public function postChangeStatus(PageRepository $repository)
    {
        $pageId = $this->getRequiredParameter('page_id');
        $value = $this->getRequiredParameter('value');

        $page = $repository->update($pageId, [
            'status' => $value,
        ]);

        $this->setContent($page->getStatus());
    }

    /**
     * @param PageRepository $repository
     */
    public function getSearch(PageRepository $repository)
    {
        $query = trim($this->getRequiredParameter('search'));

        $pages = $repository->searchByKeyword($query);
        $children = [];

        foreach ($pages as $page) {
            $page->isExpanded = false;
            $page->hasChildren = false;

            $children[] = $page;
        }

        $this->setContent(view('pages::pages.children', [
            'children' => $children,
            'level'     => 0,
        ]));
    }
}
