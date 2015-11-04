<?php

namespace KodiCMS\Pages\Contracts;

interface BehaviorInterface
{
    /**
     * @return FrontendPage
     */
    public function getPage();

    /**
     * @return array
     */
    public function routeList();

    /**
     * @return Router
     */
    public function getRouter();

    /**
     * @param string $uri
     *
     * @return string
     */
    public function executeRoute($uri);

    /**
     * @return Settings
     */
    public function getSettings();

    /**
     * @return string
     */
    public function getSettingsTemplate();
}
