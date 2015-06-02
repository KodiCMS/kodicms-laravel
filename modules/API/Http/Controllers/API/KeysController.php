<?php namespace KodiCMS\API\Http\Controllers\API;

use DatabaseConfig;
use KodiCMS\API\Model\ApiKey;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\System\Controller;

class KeysController extends Controller
{
	public function getKeys()
	{
		if (!acl_check('system.api.view_keys'))
		{
			throw new PermissionException;
		}

		$keys = ApiKey::lists('description', 'id');
		$systemKey = config('cms.api_key');

		unset($keys[$systemKey]);

		$this->setContent($keys);
	}

	public function putKey()
	{
		if (!acl_check('system.api.create_keys'))
		{
			throw new PermissionException;
		}

		$description = $this->getRequiredParameter('description');
		$this->setContent(ApiKey::generate($description));
	}

	public function deleteKey()
	{
		if (!acl_check('system.api.delete_keys'))
		{
			throw new PermissionException;
		}

		$systemKey = config('cms.api_key');
		$key = $this->getRequiredParameter('key');

		if ($key == $systemKey)
		{
			throw new PermissionException;
		}

		$this->setContent((bool) ApiKey::where('id', $key)->delete());
	}

	public function postRefresh()
	{
		if (!acl_check('system.api.refresh_key'))
		{
			throw new PermissionException;
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