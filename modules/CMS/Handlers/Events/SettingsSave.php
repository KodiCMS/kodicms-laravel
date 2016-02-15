<?php

namespace KodiCMS\CMS\Handlers\Events;

use DatabaseConfig;

class SettingsSave
{
    /**
     * Handle the event.
     *
     * @param array $settings
     */
    public function handle(array $settings)
    {
        DatabaseConfig::save($settings);
    }
}
