<?php

namespace KodiCMS\Plugins\Http\Controllers;

use Meta;
use PluginLoader;
use KodiCMS\Plugins\Loader\BasePluginContainer;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class PluginController extends BackendController
{
    public function getIndex()
    {
        Meta::loadPackage('backbone');
        $this->setContent('list');
    }

    /**
     * @param string $pluginId
     *
     * @return $this
     */
    public function getSettings($pluginId)
    {
        $plugin = $this->getPlugin($pluginId);
        $this->setTitle(
            trans($this->wrapNamespace('core.plugin_settings_page'), [
                'title' => $plugin->getTitle(),
            ])
        );

        $settingsTemplate = $plugin->getSettingsTemplate();

        $this->setContent('settings', compact('settingsTemplate', 'plugin'));
    }

    /**
     * @param string $pluginId
     *
     * @return $this
     */
    public function postSettings($pluginId)
    {
        $plugin = $this->getPlugin($pluginId);

        $settings = $this->request->get('settings', []);

        $plugin->saveSettings($settings);

        return $this->smartRedirect([], 'backend.plugins.list')
            ->with('success', trans($this->wrapNamespace('core.messages.settings_saved'), [
                'title' => $plugin->getTitle(),
            ]));
    }

    /**
     * @param $pluginId
     *
     * @return $this|BasePluginContainer
     */
    protected function getPlugin($pluginId)
    {
        if (is_null($plugin = PluginLoader::getPluginContainer($pluginId))) {
            return back(404)->withErrors(["Plugin [{$pluginId}] not found"]);
        }

        if (! $plugin->hasSettingsPage()) {
            return back(404)->withErrors(["Plugin [{$pluginId}] has not settings page"]);
        }

        return $plugin;
    }
}
