<?php

namespace KodiCMS\CMS\Console\Commands;

use ModulesLoader;
use RuntimeException;
use Illuminate\Console\GeneratorCommand;
use KodiCMS\Support\Loader\ModuleContainer;
use Symfony\Component\Console\Input\InputOption;

class ControllerMakeCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'cms:make:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * @var ModuleContainer
     */
    protected $module;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->module = $this->findModule();

        parent::fire();
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getPath($name)
    {
        $name = str_replace($this->getAppNamespace(), '', $name);

        return $this->module->getPath(str_replace('\\', '/', $name).'.php');
    }

    public function findModule()
    {
        $module = $this->input->getOption('module');

        foreach (ModulesLoader::getRegisteredModules() as $moduleContainer) {
            if ($moduleContainer->getKey() == strtolower($module)) {
                return $moduleContainer;
            }
        }

        throw new RuntimeException("Module {$module} not found.");
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        $type = $this->input->getOption('type');

        switch ($type) {
            case 'api':
                return __DIR__.'/stubs/controller.api.stub';
            default:
                return __DIR__.'/stubs/controller.stub';
        }
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        $type = $this->input->getOption('type');

        switch ($type) {
            case 'api':
                return $rootNamespace.'\Http\Controllers\API';
            default:
                return $rootNamespace.'\Http\Controllers';
        }
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['module', null, InputOption::VALUE_REQUIRED, 'Module name'],
            ['type', 'backend', InputOption::VALUE_OPTIONAL, 'Controller type [backend, api]'],
        ];
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    protected function getAppNamespace()
    {
        return $this->module->getNamespace();
    }
}
