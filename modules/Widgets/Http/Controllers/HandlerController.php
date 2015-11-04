<?php

namespace KodiCMS\Widgets\Http\Controllers;

use KodiCMS\CMS\Exceptions\ValidationException;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Repository\WidgetRepository;
use KodiCMS\CMS\Http\Controllers\System\Controller;

class HandlerController extends Controller
{
    /**
     * @param WidgetRepository $repository
     * @param int              $handlerId
     *
     * @return mixed|void
     * @throws WidgetException
     */
    public function getHandle(WidgetRepository $repository, $handlerId)
    {
        event('handler.requested', [$handlerId]);

        $widget = $repository->findOrFail($handlerId);

        if (! $widget->isHandler()) {
            throw new WidgetException('Widget handler not found');
        }

        try {
            return app()->call([$widget->toWidget(), 'handle']);
        } catch (ValidationException $e) {
            return $this->throwValidationException($this->request, $e->getValidator());
        }
    }
}
