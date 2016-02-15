<?php

namespace KodiCMS\CMS\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\TableSeparator;

class ModuleLocaleDiffCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:modules:locale:diff';

    /**
     * The table headers for the command.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire(Filesystem $files)
    {
        $langDirectory = base_path(
            'resources'.DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR
        );

        $diff = [];

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            if (! is_dir($module->getLocalePath()) or ! $module->isPublishable()) {
                continue;
            }

            $locale = $this->input->getOption('locale');

            foreach ($files->directories($module->getLocalePath()) as $localeDir) {
                foreach ($files->allFiles($localeDir) as $localeFile) {
                    $vendorFileDir = $module->getKey().DIRECTORY_SEPARATOR.$locale.DIRECTORY_SEPARATOR.$localeFile->getFilename();
                    $vendorFilePath = $langDirectory.$vendorFileDir;
                    if (file_exists($vendorFilePath)) {
                        $localArray = $files->getRequire($localeFile->getRealPath());
                        $vendorArray = $files->getRequire($vendorFilePath);

                        $array = array_keys_exists_recursive($localArray, $vendorArray);
                        $arrayDiff = '';
                        foreach (array_dot($array) as $key => $value) {
                            $arrayDiff .= "{$key}: {$value}\n";
                        }

                        if (empty($arrayDiff)) {
                            continue;
                        }

                        $diff[] = [
                            'modules'.DIRECTORY_SEPARATOR.$vendorFileDir,
                            'vendor'.DIRECTORY_SEPARATOR.$vendorFileDir,
                        ];
                        $diff[] = new TableSeparator;
                        $diff[] = [$arrayDiff, var_export(array_merge_recursive($array, $vendorArray), true)];
                        $diff[] = new TableSeparator;
                    }
                }
            }
        }

        $this->table($this->headers, $diff);
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['locale', 'l', InputOption::VALUE_REQUIRED],
        ];
    }
}
