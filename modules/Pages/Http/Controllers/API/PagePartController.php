<?php

namespace KodiCMS\Pages\Http\Controllers\API;

use KodiCMS\Pages\Repository\PagePartRepository;
use KodiCMS\API\Http\Controllers\System\Controller;

class PagePartController extends Controller
{
    /**
     * @param PagePartRepository $repository
     */
    public function getByPageId(PagePartRepository $repository)
    {
        $pageId = $this->getRequiredParameter('pid');
        $parts = $repository->findByPageId($pageId);

        $this->setContent($parts->toArray());
    }

    /**
     * @param PagePartRepository $repository
     */
    public function create(PagePartRepository $repository)
    {
        $part = $repository->create($this->request->all());
        $this->setContent($part->toArray());
    }

    /**
     * @param PagePartRepository $repository
     * @param int            $id
     */
    public function update(PagePartRepository $repository, $id)
    {
        $part = $repository->update($id, $this->request->all());
        $this->setContent($part->toArray());
    }

    /**
     * @param PagePartRepository $repository
     * @param int            $id
     */
    public function delete(PagePartRepository $repository, $id)
    {
        $repository->delete($id);
    }

    /**
     * @param PagePartRepository $repository
     */
    public function reorder(PagePartRepository $repository)
    {
        if (! acl_check('parts.reorder')) {
            return;
        }

        $ids = $this->getParameter('ids', []);
        $repository->reorder($ids);
    }
}
