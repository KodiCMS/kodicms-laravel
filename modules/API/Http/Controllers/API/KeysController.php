<?php

namespace KodiCMS\API\Http\Controllers\API;

use KodiCMS\API\Exceptions\Exception;
use KodiCMS\Api\Repository\ApiKeyRepository;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\System\Controller;

class KeysController extends Controller
{
    /**
     * @param ApiKeyRepository $repository
     */
    public function getKeys(ApiKeyRepository $repository)
    {
        if (! acl_check('api.view_keys')) {
            throw new PermissionException('api.view_keys');
        }

        $keys = $repository->getList();
        unset($keys[$repository->getSystemKey()]);

        $this->setContent($keys);
    }

    /**
     * @param ApiKeyRepository $repository
     */
    public function putKey(ApiKeyRepository $repository)
    {
        if (! acl_check('api.create_keys')) {
            throw new PermissionException('api.create_keys');
        }

        $description = $this->getRequiredParameter('description');
        $this->setContent($repository->generate($description));
    }

    /**
     * @param ApiKeyRepository $repository
     */
    public function deleteKey(ApiKeyRepository $repository)
    {
        if (! acl_check('api.delete_keys')) {
            throw new PermissionException('api.delete_keys');
        }

        $key = $this->getRequiredParameter('key');

        if ($repository->isSystemKey($key)) {
            throw new Exception(trans('api.core.messages.system_api_remove'));
        }

        $this->setContent((bool) $repository->deleteByKey($key));
    }

    /**
     * @param ApiKeyRepository $repository
     */
    public function postRefresh(ApiKeyRepository $repository)
    {
        if (! acl_check('api.refresh_key')) {
            throw new PermissionException('api.refresh_key');
        }

        $key = $repository->getSystemKey();

        if (! $repository->isValid($key)) {
            $key = $repository->generate();
        } else {
            $key = $repository->refresh($key);
        }

        $this->setContent($key);
    }
}
