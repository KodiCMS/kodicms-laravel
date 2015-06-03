<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\Pages\Model\Page;
use KodiCMS\Users\Model\UserMeta;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;

class PageController extends APIController
{
	public function getChildren()
	{
		$this->setContent($this->_children((int)$this->getRequiredParameter('parent_id'), (int)$this->getParameter('level')));
	}

	protected function _children($parentId, $level)
	{
		$expandedRows = UserMeta::get('expanded_pages', []);

		$page = Page::find($parentId);

		if (is_null($page))
		{
			return null;
		}

		$query = Page::where('parent_id', $parentId)->orderBy('position', 'asc')->orderBy('created_at', 'asc');

		$childrens = $query->get()->lists(null, 'id');

		foreach ($childrens as $id => $child)
		{
			$childrens[$id]->hasChildren = $child->hasChildren();
			$childrens[$id]->isExpanded = in_array($child->id, $expandedRows);

			if ($childrens[$id]->isExpanded === true)
			{
				$childrens[$id]->childrenRows = $this->_children($child->id, $level + 1);
			}
		}

		return view('pages::pages.children', [
			'childrens' => $childrens,
			'level'     => $level + 1
		])->render();
	}

	public function getReorder()
	{
		$pages = PageSitemap::get(true)->asArray();

		$this->setContent(view('pages::pages.reorder', [
			'pages' => $pages
		]));
	}

	public function postReorder()
	{
		$pages = $this->getRequiredParameter('pids', []);

		if (empty($pages)) return;

		$this->setContent((new Page)->reorder($pages));
	}

	public function postChangeStatus()
	{
		$page_id = $this->getRequiredParameter('page_id');
		$value = $this->getRequiredParameter('value');

		$page = Page::find($page_id);
		$page->update([
			'status' => $value
		]);

		$this->setContent($page->getStatus());
	}

	public function getSearch()
	{
		$query = trim($this->getRequiredParameter('search'));

		$pages = new Page;

		if (strlen($query) == 2 AND $query[0] == '.')
		{
			$page_status = [
				'd' => FrontendPage::STATUS_DRAFT,
				'p' => FrontendPage::STATUS_PUBLISHED,
				'h' => FrontendPage::STATUS_HIDDEN
			];

			if (isset($page_status[$query[1]]))
			{
				$pages->whereIn('status', $page_status[$query[1]]);
			}
		} else
		{
			$pages = $pages->searchByKeyword($query);
		}

		$childrens = [];
		$pages = $pages->get();

		foreach ($pages as $page)
		{
			$page->isExpanded = false;
			$page->hasChildren = false;

			$childrens[] = $page;
		}

		$this->setContent((string)view('pages::pages.children', [
			'childrens' => $childrens,
			'level'     => 0
		]));
	}
}