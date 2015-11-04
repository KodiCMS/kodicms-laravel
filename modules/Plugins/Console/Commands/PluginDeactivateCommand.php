<?php

namespace KodiCMS\Plugins\Console\Commands;

use PluginLoader;
use Illuminate\Console\Command;

class PluginDeactivateCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'cms:plugins:deactivate {plugin} {--removetable=no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate plugin';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $pluginName = $this->argument('plugin');

        $removeTable = $this->option('removetable');

        if (PluginLoader::deactivatePlugin($pluginName, $removeTable != 'no')) {
            $this->info("Plugin [{$pluginName}] deactivated");

            return;
        }

        $this->error("Can't deactivate plugin: [{$pluginName}]");
    }
}
