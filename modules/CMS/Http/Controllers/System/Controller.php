<?php

namespace KodiCMS\CMS\Http\Controllers\System;

use Illuminate\Http\RedirectResponse;
use KodiCMS\Support\Traits\ControllerACL;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use KodiCMS\Support\Traits\Controller as ControllerTrait;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests, AuthorizesRequests, ControllerTrait, ControllerACL;

    public function initMiddleware()
    {
        if ($this->authRequired) {
            $this->middleware('backend.auth');
        }
    }

    /**
     * @param array       $parameters
     * @param string|null $route
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function smartRedirect(array $parameters = [], $route = null)
    {
        $isContinue = ! is_null($this->request->get('continue'));

        if ($route === null) {
            if ($isContinue) {
                $route = action('\\'.get_called_class().'@getEdit', $parameters);
            } else {
                $route = action('\\'.get_called_class().'@getIndex');
            }
        } elseif (strpos($route, '@') !== false) {
            $route = action($route, $parameters);
        } else {
            $route = route($route, $parameters);
        }

        if ($isContinue and $this->getCurrentAction() != 'postCreate') {
            return back();
        }

        return redirect($route);
    }

    /**
     * @param RedirectResponse $response
     *
     * @throws HttpResponseException
     */
    public function throwFailException(RedirectResponse $response)
    {
        throw new HttpResponseException($response);
    }
}
