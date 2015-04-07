<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PageSitemap;
use KodiCMS\Users\Model\UserMeta;

class PageController extends APIController
{
	/**
	 * @var bool
	 */
	public $authRequired = TRUE;

	public function getChildren()
	{
		$this->setContent($this->_children((int)$this->getRequiredParameter('parent_id'), (int)$this->getParameter('level')));
	}

	protected function _children($parent_id, $level)
	{
		$expandedRows = UserMeta::get('expanded_pages', []);

		$page = Page::find($parent_id);

		if (is_null($page)) {
			return NULL;
		}

		$query = Page::where('parent_id', $parent_id)
			->orderBy('position', 'asc')
			->orderBy('created_at', 'asc');

		$childrens = $query->get()->lists(NULL, 'id');

		foreach ($childrens as $id => $child) {
			$childrens[$id]->hasChildren = $child->hasChildren();
			$childrens[$id]->isExpanded = in_array($child->id, $expandedRows);

			if ($childrens[$id]->isExpanded === TRUE) {
				$childrens[$id]->childrenRows = $this->_children($child->id, $level + 1);
			}
		}

		return (string)view('pages::pages.children', [
			'childrens' => $childrens,
			'level' => $level + 1
		]);
	}

	public function getReorder()
	{
		$pages = PageSitemap::get(TRUE)->asArray();

		$this->setContent(view('pages::pages.reorder', [
			'pages' => $pages
		]));
	}

	public function postReorder()
	{
		$pages = $this->getRequiredParameter('pids', []);

		if (empty($pages)) return;

		$pages = array_map(function ($page) {
			$page['parent_id'] = empty($page['parent_id']) ? 1 : $page['parent_id'];
			$page['id'] = (int)$page['id'];
			$page['position'] = (int)$page['position'];

			return $page;
		}, $pages);

		$builder = \DB::table('pages');
		$grammar = $builder->getGrammar();
		$insert = $grammar->compileInsert($builder, $pages);

		$bindings = [];

		foreach ($pages as $record) {
			foreach ($record as $value) {
				$bindings[] = $value;
			}
		}
		$insert = $insert . ' ON DUPLICATE KEY UPDATE parent_id = VALUES(parent_id), position = VALUES(position)';

		\DB::insert($insert, $bindings);
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

		if (strlen($query) == 2 AND $query[0] == '.') {
			$page_status = [
				'd' => FrontendPage::STATUS_DRAFT,
				'p' => FrontendPage::STATUS_PUBLISHED,
				'h' => FrontendPage::STATUS_HIDDEN
			];

			if (isset($page_status[$query[1]])) {
				$pages->whereIn('status', $page_status[$query[1]]);
			}
		} else {
			$pages = $pages->searchByKeyword($query);
		}

		$childrens = [];
		$pages = $pages->get();

		foreach ($pages as $page) {
			$page->isExpanded = FALSE;
			$page->hasChildren = FALSE;

			$childrens[] = $page;
		}

		$this->setContent((string)view('pages::pages.children', [
			'childrens' => $childrens,
			'level' => 0
		]));
	}
}