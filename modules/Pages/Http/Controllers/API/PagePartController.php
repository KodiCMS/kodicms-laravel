<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Pages\Model\PagePart;

class PagePartController extends APIController
{
	public function getByPageId()
	{
		$pageId = $this->getRequiredParameter('pid');

		$parts = PagePart::where('page_id', (int) $pageId)->get();

		$this->setContent($parts->toArray());
	}

	public function put()
	{
		$partId = $this->getRequiredParameter('id');
		$part = PagePart::findOrFail($partId);

		$part->update($this->request->all());

		$this->setContent($part->toArray());
	}

	public function post()
	{
		$part = new PagePart;
		$part->create($this->request->all());

		$this->setContent($part->toArray());
	}

	public function delete()
	{
		$partId = $this->getRequiredParameter('id');
		PagePart::findOrFail($partId)->delete();
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