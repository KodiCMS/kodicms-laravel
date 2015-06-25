<?php namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\Widgets\Repository\WidgetRepository;
use KodiCMS\CMS\Http\Controllers\System\Controller;

class HandlerController extends Controller
{
	public function getHandle(WidgetRepository $repository, $handlerId)
	{
		event('handler.requested', [$handler]);

		$widget = $repository->findOrFail($handlerId);

		if (!$widget->isHandler())
		{

		}

		$widget->handle();
	}
}