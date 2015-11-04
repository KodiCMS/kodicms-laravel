<?php

namespace KodiCMS\Pages\Http\Controllers\System;

use CMS;
use Illuminate\Http\Response;
use KodiCMS\Pages\Helpers\Block;
use Illuminate\Contracts\View\View;
use KodiCMS\Pages\Model\LayoutCollection;
use KodiCMS\Widgets\Collection\WidgetCollection;
use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Pages\Exceptions\LayoutNotFoundException;

abstract class FrontPageController extends Controller
{
    /**
     * @var WidgetCollection;
     */
    protected $widgetCollection;

    /**
     * Execute before an action executed
     * return void.
     */
    public function before()
    {
        $this->widgetCollection = $collection = new WidgetCollection;
        app()->singleton('layout.block', function ($app) use ($collection) {
            return new Block($collection);
        });
    }

    /**
     * @param string $layout
     *
     * @return View
     * @throws LayoutNotFoundException
     */
    protected function getLayoutFile($layout)
    {
        if (is_null($layout = (new LayoutCollection)->findFile($layout))) {
            throw new LayoutNotFoundException(
                trans('pages::core.messages.layout_not_set')
            );
        }

        return $layout->toView();
    }

    /**
     * @param View   $layout
     * @param string $mime
     *
     * @return \Illuminate\View\View|null
     * @throws LayoutNotFoundException
     */
    protected function render(View $layout = null, $mime = 'text\html')
    {
        if (is_null($layout)) {
            throw new LayoutNotFoundException(
                trans('pages::core.messages.layout_not_set')
            );
        }

        $html = $layout->render();
        if (auth()->check() and auth()->user()->hasRole(['administrator', 'developer'])) {
            $injectHTML = (string) view('cms::app.partials.toolbar');
            // Insert system HTML before closed tag body
            $matches = preg_split('/(<\/body>)/i', $html, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

            if (count($matches) > 1) {
                /* assemble the HTML output back with the iframe code in it */
                $html = $matches[0].$injectHTML.$matches[1].$matches[2];
            }
        }

        $response = new Response();

        $response->header('Content-Type', $mime);

        if (config('cms.show_response_sign', true)) {
            $response->header('X-Powered-CMS', CMS::NAME.'/'.CMS::VERSION);
        }

        $response->setContent($html);

        // Set the ETag header
        $response->setEtag(md5($html));

        // mark the response as either public or private
        $response->setPublic();

        // Check that the Response is not modified for the given Request
        if ($response->isNotModified($this->request)) {
            // return the 304 Response immediately
            return $response;
        }

        return $response;
    }

    /**
     * Execute an action on the controller.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        if ($method != 'run') {
            $this->before();
        }

        $response = call_user_func_array([$this, $method], $parameters);

        if ($method != 'run') {
            $this->after($response);
        }

        return $response;
    }
}
