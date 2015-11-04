<?php

namespace KodiCMS\Plugins\Loader;

use Schema;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Database\ConnectionResolverInterface as Resolver;

class PluginInstaller extends Migrator
{
    /**
     * @param Resolver   $resolver
     * @param Filesystem $files
     */
    public function __construct(Resolver $resolver, Filesystem $files)
    {
        $this->files = $files;
        $this->resolver = $resolver;
    }

    /**
     * @param string $path
     */
    public function installSchemas($path)
    {
        $migrations = $this->getMigrationFiles($path);
        $this->requireFiles($path, $migrations);

        foreach ($migrations as $file) {
            $this->runUp($file, null, false);
        }
    }

    /**
     * @param string $path
     */
    public function dropSchemas($path)
    {
        $migrations = $this->getMigrationFiles($path);
        $this->requireFiles($path, $migrations);

        foreach ($migrations as $migration) {
            $this->runDown($migration, false);
        }
    }

    /**
     * Get all of the migration files in a given path.
     *
     * @param  string $path
     *
     * @return array
     */
    public function getMigrationFiles($path)
    {
        $files = $this->files->glob($path.'/*.php');

        if ($files === false) {
            return [];
        }

        $files = array_map(function ($file) {
            return str_replace('.php', '', basename($file));

        }, $files);

        sort($files);

        return $files;
    }

    /**
     * Resolve a migration instance from a file.
     *
     * @param  string $file
     *
     * @return object
     */
    public function resolve($file)
    {
        $class = studly_case($file);

        return new $class;
    }

    /**
     * Run "up" a migration instance.
     *
     * @param  string $file
     * @param  int    $batch
     * @param  bool   $pretend
     *
     * @return void
     */
    protected function runUp($file, $batch, $pretend)
    {
        $migration = $this->resolve($file);

        if ($migration instanceof PluginSchema) {
            $tableName = $migration->getTableName();

            if (! Schema::hasTable($tableName)) {
                $migration->up();
            }
        }
    }

    /**
     * Run "down" a migration instance.
     *
     * @param  string $file
     * @param  bool   $pretend
     *
     * @return void
     */
    protected function runDown($file, $pretend)
    {
        $migration = $this->resolve($file);
        $migration->down();
    }
}
