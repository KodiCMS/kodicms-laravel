<?php namespace KodiCMS\API\Http\Controllers\API;

use DatabaseConfig;
use KodiCMS\API\Model\ApiKey;
use KodiCMS\API\Exceptions\Exception;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\System\Controller;

class KeysController extends Controller
{
	public function getKeys()
	{
		if (!acl_check('api.view_keys'))
		{
			throw new PermissionException('api.view_keys');
		}

		$keys = ApiKey::lists('description', 'id')->all();
		$systemKey = config('cms.api_key');

		unset($keys[$systemKey]);

		$this->setContent($keys);
	}

	public function putKey()
	{
		if (!acl_check('api.create_keys'))
		{
			throw new PermissionException('api.create_keys');
		}

		$description = $this->getRequiredParameter('description');
		$this->setContent(ApiKey::generate($description));
	}

	public function deleteKey()
	{
		if (!acl_check('api.delete_keys'))
		{
			throw new PermissionException('api.delete_keys');
		}

		$systemKey = config('cms.api_key');
		$key = $this->getRequiredParameter('key');

		if ($key == $systemKey)
		{
			throw new Exception(trans('api.core.messages.system_api_remove'));
		}

		$this->setContent((bool) ApiKey::where('id', $key)->delete());
	}

	public function postRefresh()
	{
		if (!acl_check('api.refresh_key'))
		{
			throw new PermissionException('api.refresh_key');
		}

		$key = config('cms.api_key');

		if (is_null($key) or !ApiKey::isValid($key))
		{
			$key = ApiKey::generate();
		}
		else
		{
			$key = ApiKey::refresh($key);
		}

		DatabaseConfig::set('cms', 'api_key', $key);

		$this->setContent($key);
	}
}