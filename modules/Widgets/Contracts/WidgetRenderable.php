<?php

namespace KodiCMS\Widgets\Contracts;

interface WidgetRenderable extends Widget
{
    /**
     * @return string
     */
    public function getFrontendTemplate();

    /**
     * @return string
     */
    public function getDefaultFrontendTemplate();

    /**
     * @return mixed
     */
    public function getMediaPackages();

    /**
     * @return array
     */
    public function prepareData();
}
