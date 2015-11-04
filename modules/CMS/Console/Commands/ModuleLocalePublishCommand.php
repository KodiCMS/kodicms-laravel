<?php

namespace KodiCMS\CMS\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class ModuleLocalePublishCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:modules:locale:publish';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire(Filesystem $files)
    {
        $data = [];

        if (is_null($fromLocale = $this->input->getOption('from'))) {
            $fromLocale = 'ru';
        }

        $newLocale = $this->input->getOption('locale');

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            if (! is_dir($module->getLocalePath()) or ! $module->isPublishable()) {
                continue;
            }

            foreach ($files->directories($module->getLocalePath()) as $localeDir) {
                $locale = basename($localeDir);

                if ($locale != $fromLocale) {
                    continue;
                }

                foreach ($files->allFiles($localeDir) as $localeFile) {
                    $data[$module->getKey()][] = $localeFile->getRealPath();
                }
            }
        }

        $langDirectory = base_path('/resources/lang/vendor');

        foreach ($data as $namespace => $locales) {
            foreach ($locales as $group => $file) {
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $fileDir = $langDirectory.DIRECTORY_SEPARATOR.$namespace.DIRECTORY_SEPARATOR.$newLocale;
                if (! $files->exists($fileDir)) {
                    $files->makeDirectory($fileDir, 0755, true);
                }

                $to = $fileDir.DIRECTORY_SEPARATOR.$filename.'.php';
                $files->copy($file, $fileDir.DIRECTORY_SEPARATOR.$filename.'.php');
                $this->line("<info>Copied {$namespace}:{$filename}</info> <comment>[{$file}]</comment> <info>To</info> <comment>[{$to}]</comment>");
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
            ['from', null, InputOption::VALUE_OPTIONAL, 'Locale that you want to publish.', 'ru'],
            ['locale', null, InputOption::VALUE_OPTIONAL, 'Locale package to be generated', 'en'],
        ];
    }
}
