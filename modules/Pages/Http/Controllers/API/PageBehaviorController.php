<?php namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;

class PageBehaviorController extends APIController
{
	public function getSettings()
	{
		$pageId = $this->getParameter('pid');
		$behavior = $this->getRequiredParameter('behavior');

		if (!is_null($behavior = BehaviorManager::load($behavior)))
		{
			$page = Page::findOrNew($pageId);
			if ($page->hasBehavior())
			{
				$behavior->setPage($page);
			}
		}

		return $this->setContent($behavior->getSettings()->render());
	}
}