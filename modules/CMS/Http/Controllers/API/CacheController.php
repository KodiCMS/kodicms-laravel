<?php namespace KodiCMS\CMS\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use Artisan;

class CacheController extends Controller
{
	public function deleteClear()
	{
		Artisan::call('cache:clear');
		$this->setMessage(trans('cms::core.messages.cache_clear'));
	}
}