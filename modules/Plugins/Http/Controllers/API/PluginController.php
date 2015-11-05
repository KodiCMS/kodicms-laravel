<?php

namespace KodiCMS\Plugins\Http\Controllers\API;

use KodiCMS\Plugins\Exceptions\PluginContainerException;
use PluginLoader;
use KodiCMS\API\Exceptions\PermissionException;
use KodiCMS\API\Http\Controllers\System\Controller;

class PluginController extends Controller
{
    public function getList()
    {
        if (! acl_check('backend.plugins.list')) {
            throw new PermissionException('backend.plugins.list');
        }

        $plugins = [];
        foreach (PluginLoader::findPlugins() as $plugin) {
            $plugins[] = $plugin->toArray();
        }

        $this->setContent($plugins);
    }

    /**
     * @throws PluginContainerException
     */
    public function changeStatus()
    {
        if (! acl_check('plugins.change_status')) {
            throw new PermissionException('plugins.change_status');
        }

        $name = $this->getRequiredParameter('name');
        $removeTable = $this->getParameter('remove_data');

        if (is_null($plugin = PluginLoader::getPluginContainer($name))) {
            throw new PluginContainerException("Plugin [{$name}] not found");
        }

        if (PluginLoader::isActivated($name)) {
            PluginLoader::deactivatePlugin($name, (bool) $removeTable);
        } else {
            PluginLoader::activatePlugin($name);
        }

        $this->setContent($plugin);
    }
}
