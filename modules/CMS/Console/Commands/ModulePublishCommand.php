<?php

namespace KodiCMS\CMS\Console\Commands;

use ModulesLoader;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Foundation\Console\VendorPublishCommand;

class ModulePublishCommand extends VendorPublishCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:modules:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish any publishable assets from modules';

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = null;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $module = $this->option('module');
        $paths = [];

        if (! is_null($module)) {
            if (! is_null($module = ModulesLoader::getRegisteredModule($this->option('module')))) {
                $paths = $module->getPublishPath();
            }
        } else {
            foreach (ModulesLoader::getRegisteredModules() as $module) {
                $paths = array_merge($paths, $module->getPublishPath());
            }
        }

        if (empty($paths)) {
            return $this->comment('Nothing to publish.');
        }

        foreach ($paths as $from => $to) {
            if ($this->files->isFile($from)) {
                $this->publishFile($from, $to);
            } elseif ($this->files->isDirectory($from)) {
                $this->publishDirectory($from, $to);
            } else {
                $this->error("Can't locate path: <{$from}>");
            }
        }

        $this->info('Publishing Complete!');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Overwrite any existing files.'],
            ['module', null, InputOption::VALUE_OPTIONAL, 'The module that has assets you want to publish.'],
        ];
    }
}
