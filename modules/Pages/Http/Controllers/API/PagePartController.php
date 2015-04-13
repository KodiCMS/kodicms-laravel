<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Pages\Model\PagePart;

class PagePartController extends Controller
{
	public function getByPageId()
	{
		$pageId = $this->getRequiredParameter('pid');

		$parts = PagePart::where('page_id', (int) $pageId)->get();

		$this->setContent($parts->toArray());
	}

	public function create()
	{
		$part = PagePart::create($this->request->all());
		$this->setContent($part->toArray());
	}

	public function update($id)
	{
		$part = PagePart::findOrFail($id);
		$part->update($this->request->all());
		$this->setContent($part->toArray());
	}

	public function delete($id)
	{
		PagePart::findOrFail($id)->delete();
	}

	public function reorder()
	{
		if (!acl_check('parts.reorder'))
		{
			return;
		}

		$ids = $this->getParameter('ids', []);
		$part = new PagePart;
		$part->reorder($ids);
	}
}