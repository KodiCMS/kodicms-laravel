<?php namespace KodiCMS\API\Http\Controllers\API;

use KodiCMS\API\Model\ApiKey;
use KodiCMS\CMS\Helpers\DatabaseConfig;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\System\Controller;

class KeysController extends Controller
{
	public function postRefresh()
	{
		if (!acl_check('system.api.refresh_key'))
		{
			throw new PermissionException;
		}

		$key = config('cms.api_key');

		if (is_null($key))
		{
			$key = ApiKey::generate();
		}
		else
		{
			$key = ApiKey::refresh($key);
		}

		DatabaseConfig::save(['cms' => ['api_key' => $key]]);

		$this->setContent($key);
	}
}