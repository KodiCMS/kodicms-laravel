<?php

namespace Plugins\butschster\News\Repository;

use Plugins\butschster\News\Model\News;
use KodiCMS\CMS\Repository\BaseRepository;
use Plugins\butschster\News\Model\NewsContent;

class NewsRepository extends BaseRepository
{
    /**
     * @param News $model
     */
    public function __construct(News $model)
    {
        parent::__construct($model);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'title' => 'required',
            'slug'  => 'required|max:100|unique:news',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param int $id
     * @param array   $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate($id, array $data = [])
    {
        $validator = $this->validator($data, [
            'title' => 'required',
            'slug'  => "required|max:100|unique:news,slug,{$id}",
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data = [])
    {
        $news = parent::create(array_only($data, [
            'title',
            'slug',
        ]));

        $news->content()->save(new NewsContent($data['content']));

        return $news;
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function update($id, array $data = [])
    {
        $news = parent::update($id, array_only($data, [
            'title',
            'slug',
        ]));

        $news->content->update($data['content']);

        return $news;
    }
}
