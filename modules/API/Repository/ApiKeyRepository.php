<?php

namespace KodiCMS\Api\Repository;

use DatabaseConfig;
use KodiCMS\Api\Model\ApiKey;
use KodiCMS\CMS\Repository\BaseRepository;

class ApiKeyRepository extends BaseRepository
{
    /**
     * @param ApiKey $model
     */
    public function __construct(ApiKey $model)
    {
        parent::__construct($model);
    }

    /**
     * @return string|null
     */
    public function getSystemKey()
    {
        return config('cms.api_key');
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isSystemKey($key)
    {
        return $this->getSystemKey() == $key;
    }

    /**
     * @param string $description
     *
     * @return string
     */
    public function generate($description = '')
    {
        $key = $this->model->generate($description);
        $this->putInConfig($key);

        return $key;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function refresh($key)
    {
        $key = $this->model->refresh($key);
        $this->putInConfig($key);

        return $key;
    }

    /**
     * @return array
     */
    public function getList()
    {
        return $this->getModel()
            ->lists('description', 'id')
            ->all();
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function deleteByKey($key)
    {
        return $this->getModel()
            ->where('id', $key)
            ->delete();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function isValid($key)
    {
        return ! is_null($key) && $this->getModel()->isValid($key);
    }

    /**
     * @param string $key
     */
    protected function putInConfig($key)
    {
        DatabaseConfig::set('cms', 'api_key', $key);
    }
}
