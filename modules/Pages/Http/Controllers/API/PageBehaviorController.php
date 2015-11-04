<?php

namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\Pages\Behavior\Manager as BehaviorManager;
use KodiCMS\API\Http\Controllers\System\Controller as APIController;
use KodiCMS\Pages\Repository\PageRepository;

class PageBehaviorController extends APIController
{
    /**
     * @param PageRepository $pageRepository
     *
     * @throws \KodiCMS\Pages\Exceptions\BehaviorException
     */
    public function getSettings(PageRepository $pageRepository)
    {
        $pageId = $this->getParameter('pid');
        $behavior = $this->getRequiredParameter('behavior');

        if (! is_null($behavior = BehaviorManager::load($behavior))) {
            $page = $pageRepository->findOrFail($pageId);
            if ($page->hasBehavior()) {
                $behavior->setPage($page);
            }
        }

        return $this->setContent($behavior->getSettings()->render());
    }
}
