<?php

namespace KodiCMS\CMS\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateScriptTranslatesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:generate:translate:js';

    /**
     * @param Filesystem $files
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Filesystem $files)
    {
        $data = [];

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            if (! is_dir($module->getLocalePath()) or ! $module->isPublishable()) {
                continue;
            }

            $namespace = $module->getKey();

            $data = array_merge_recursive($data, $this->loadLangFromPath($files, $module->getLocalePath(), $namespace));

            $vendorPath = base_path(implode(DIRECTORY_SEPARATOR, ['resources', 'lang', 'vendor', $namespace]));

            if (is_dir($vendorPath)) {
                $data = array_merge_recursive($data, $this->loadLangFromPath($files, $vendorPath, $namespace));
            }
        }

        $langDirectory = backend_resources_path('js/locale');

        if (! $files->exists($langDirectory)) {
            $files->makeDirectory($langDirectory, 0755, true);
        }

        $this->output->progressStart(count($data));

        foreach ($data as $locale => $translates) {
            $data = json_encode($translates);
            $file = $langDirectory.DIRECTORY_SEPARATOR.$locale.'.json';
            $files->put($file, $data);

            $this->output->progressAdvance();
            $this->line("<info>File [{$file}]</info> for locale: [{$locale}] created");
        }

        $this->output->progressFinish();
    }

    /**
     * @param Filesystem $files
     * @param            $path
     * @param            $namespace
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function loadLangFromPath(Filesystem $files, $path, $namespace)
    {
        $data = [];

        foreach ($files->directories($path) as $localeDir) {
            $locale = basename($localeDir);
            foreach ($files->allFiles($localeDir) as $localeFile) {
                $data[$locale][$namespace][basename($localeFile, '.php')] = $files->getRequire($localeFile->getRealPath());
            }
        }

        return $data;
    }
}
