<?php

namespace KodiCMS\Users\Repository;

use KodiCMS\Users\Model\UserReflink;
use KodiCMS\CMS\Repository\BaseRepository;

class UserReflinkRepository extends BaseRepository
{
    /**
     * @param UserReflink $model
     */
    public function __construct(UserReflink $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $code
     *
     * @return Model|null
     */
    public function find($code)
    {
        return $this->model->where('code', $code)->find();
    }

    /**
     * @param string $code
     *
     * @return Model
     * @throws ModelNotFoundException
     */
    public function findOrFail($code)
    {
        return $this->model->where('code', $code)->findOrFail();
    }
}
