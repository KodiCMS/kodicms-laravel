<?php

namespace KodiCMS\Plugins\Console\Commands;

use PluginLoader;
use Illuminate\Console\Command;

class PluginActivateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cms:plugins:activate {plugin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Activate plugin';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $pluginName = $this->argument('plugin');

        if (PluginLoader::activatePlugin($pluginName)) {
            $this->info("Plugin [{$pluginName}] activated");

            return;
        }

        $this->error("Can't activate plugin: [{$pluginName}]");
    }
}
