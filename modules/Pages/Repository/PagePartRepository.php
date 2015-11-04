<?php

namespace KodiCMS\Pages\Repository;

use KodiCMS\Pages\Model\PagePart;
use KodiCMS\CMS\Repository\BaseRepository;

class PagePartRepository extends BaseRepository
{
    /**
     * @param PagePart $model
     */
    public function __construct(PagePart $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $pageId
     *
     * @return array
     */
    public function findByPageId($pageId)
    {
        return $this->model->where('page_id', (int) $pageId)->get();
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function reorder(array $data)
    {
        return $this->model->reorder($data);
    }
}
