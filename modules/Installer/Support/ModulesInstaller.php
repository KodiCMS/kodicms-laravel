<?php

namespace KodiCMS\Installer\Support;

use App;
use Schema;
use KodiCMS\Support\Loader\ModuleContainer;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;

class ModulesInstaller
{
    /**
     * @var array
     */
    protected $_modules = [];

    /**
     * @var Migrator
     */
    protected $_migrator;

    /**
     * @var DatabaseMigrationRepository
     */
    protected $_repository;

    /**
     * @var array
     */
    protected $_outputMessages = [];

    /**
     * @param array $modules
     */
    public function __construct(array $modules)
    {
        $this->_modules = $modules;
        $this->_migrator = App::make('migrator');
        $this->_repository = App::make('migration.repository');

        $this->init();
    }

    protected function init()
    {
        $firstUp = ! Schema::hasTable('migrations');
        if ($firstUp) {
            $this->_repository->createRepository();
            $this->output('Migration table created successfully.');
        }
    }

    protected function deinit()
    {
        Schema::dropIfExists('migrations');
        $this->output('Migration table dropped.');
    }

    /**
     * @return $this
     */
    public function migrateModules()
    {
        $this->output('Starting process of migration...');

        foreach ($this->_modules as $module) {
            $this->migrateModule($module);
        }

        return $this;
    }

    /**
     * Run migrations on a single module.
     *
     * @param ModuleContainer $module
     *
     * @return $this
     */
    public function migrateModule(ModuleContainer $module)
    {
        $this->_migrator->run($module->getPath(['database', 'migrations']));

        $this->output($module->getName());
        foreach ($this->_migrator->getNotes() as $note) {
            $this->output(' - '.$note);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function resetModules()
    {
        $this->output('Starting process of reseting...');

        foreach ($this->_modules as $module) {
            $this->addModuleToReset($module);
        }

        return $this->rollbackModules();
    }

    /**
     * Reset migrations on a single module.
     *
     * @param ModuleContainer $module
     *
     * @return $this
     */
    public function addModuleToReset(ModuleContainer $module)
    {
        $path = $module->getPath(['database', 'migrations']);
        $this->_migrator->requireFiles($path, $this->_migrator->getMigrationFiles($path));

        return $this;
    }

    /**
     * @return $this
     */
    public function rollbackModules()
    {
        while (true) {
            $count = $this->_migrator->rollback();

            foreach ($this->_migrator->getNotes() as $note) {
                $this->output($note);
            }

            if ($count == 0) {
                break;
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function seedModules(array $data = [])
    {
        foreach ($this->_modules as $module) {
            $this->seedModule($module, array_get($data, $module->getName(), []));
        }

        return $this;
    }

    /**
     * Run seeds on a module.
     *
     * @param ModuleContainer $module
     *
     * @return $this
     */
    public function seedModule(ModuleContainer $module, array $data = [])
    {
        $className = $module->getNamespace().'\\database\\seeds\\DatabaseSeeder';

        if (! class_exists($className)) {
            return false;
        }

        $seeder = app($className, $data);
        $seeder->run();

        $this->output(sprintf('<info>Seeded %s</info> ', $module));

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    protected function output($message)
    {
        $this->_outputMessages[] = $message;

        return $this;
    }

    /**
     * @return array
     */
    public function getOutputMessages()
    {
        return $this->_outputMessages;
    }

    /**
     * @return $this
     */
    public function cleanOutputMessages()
    {
        $this->_outputMessages = [];

        return $this;
    }
}
