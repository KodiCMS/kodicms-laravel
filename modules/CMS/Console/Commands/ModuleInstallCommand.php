<?php

namespace KodiCMS\CMS\Console\Commands;

use Composer;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class ModuleInstallCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:modules:install';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Execute the console command.
     *
     * @param Filesystem $files
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function fire(Filesystem $files)
    {
        $this->files = $files;

        $moduleInfo = [];

        foreach ($this->files->directories(base_path('vendor')) as $packageDir) {
            foreach ($this->files->directories($packageDir) as $dir) {
                $composerJson = json_decode($this->files->get($dir.DIRECTORY_SEPARATOR.'composer.json'), true);

                if (! isset($composerJson['type']) or $composerJson['type'] != 'kodicms-module' or ! isset($composerJson['autoload']['psr-4'])) {
                    continue;
                }

                foreach ($composerJson['autoload']['psr-4'] as $namespace => $path) {
                    $pathInfo = pathinfo($dir);
                    $moduleInfo[$pathInfo['basename']] = [
                        'namespace' => $namespace,
                        'path'      => $dir.DIRECTORY_SEPARATOR.normalize_path($path)
                    ];
                }
            }
        }

        $modulesCachePath = $this->getPath();
        $this->makeDirectory($path);

        $stub = $this->files->get($this->getStub());

        $this->files->put($modulesCachePath, str_replace('{{modules}}', var_export($moduleInfo, true), $stub));
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string $path
     *
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return base_path('bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'modules.php');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/modules.stub';
    }
}
