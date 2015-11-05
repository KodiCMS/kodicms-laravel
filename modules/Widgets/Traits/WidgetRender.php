<?php

namespace KodiCMS\Widgets\Traits;

use Illuminate\Contracts\View\View;
use KodiCMS\Widgets\Contracts\WidgetRenderEngine;

trait WidgetRender
{
    /**
     * @return string|View
     */
    public function getFrontendTemplate()
    {
        return $this->frontendTemplate;
    }

    /**
     * @return string|View
     */
    public function getDefaultFrontendTemplate()
    {
        if (is_null($this->defaultFrontendTemplate)) {
            return view('widgets::widgets.default');
        }

        return $this->defaultFrontendTemplate;
    }

    /**
     * @param string|View $template
     */
    public function setFrontendTemplate($template)
    {
        $this->frontendTemplate = $template;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return array
     */
    public function getMediaPackages()
    {
        return (array) $this->media_packages;
    }

    /**********************************************************************************************************
     * Events
     **********************************************************************************************************/

    public function onLoad()
    {
    }

    public function onRender(WidgetRenderEngine $engine)
    {
    }
}
