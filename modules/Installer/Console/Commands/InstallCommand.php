<?php

namespace KodiCMS\Installer\Console\Commands;

use Installer;
use EnvironmentTester;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;

class InstallCommand extends GeneratorCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     */
    protected $name = 'cms:install';

    /**
     * @var array
     */
    protected $env = [];

    /**
     * Configs DB.
     */
    protected $DBConfigs = [
        'driver' => 'DB_DRIVER',
        'host' => 'DB_HOST',
        'database' => 'DB_DATABASE',
        'username' => 'DB_USERNAME',
        'password' => 'DB_PASSWORD',
        'prefix' => 'DB_PREFIX',
    ];

    /**
     * @var array
     */
    protected $testHeaders = ['Title', 'Passed', 'Motice', 'Message'];

    /**
     * Execute the console command.
     */
    public function fire()
    {
        list($failed, $tests, $optional) = EnvironmentTester::check();

        $this->table($this->testHeaders, $tests);

        if (! empty($optional)) {
            $this->info('Optional tests');
            $this->table($this->testHeaders, $optional);
        }

        if ($failed) {
            throw new InstallException('Environment test failed');
        }

        if (! $this->confirmToProceed('.env file already exists!', function () {
            return cms_installed();
        })
        ) {
            return $this->error('Installation is aborted.');
        }

        $db = $this->createDBConnection();

        while (! $db && $this->confirm('Do you want enter settings?')) {
            $this->askOptions();
            $db = $this->createDBConnection();
        }

        if (! $db) {
            return $this->error('Installation is aborted.');
        }

        if (Installer::createEnvironmentFile($this->getConfig())) {
            $this->info('.env file created successfully.');
        }

        if ($this->confirm('Clear database? [yes/no]')) {
            $this->dropDatabase();
        }

        Installer::initModules();

        $this->migrate();
        if ($this->confirm('Install seed data?')) {
            $this->seed();
        }

        $this->info('Installation completed successfully');
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/env.stub';
    }

    protected function dropDatabase()
    {
        $this->call('db:clear', ['--force' => true]);
    }

    /**
     * Миграция данных.
     */
    protected function migrate()
    {
        $this->call('modules:migrate', ['--force' => true]);
    }

    /**
     * Сидирование данных.
     */
    protected function seed()
    {
        $this->call('modules:seed', ['--force' => true]);
    }

    /**
     * Ask options.
     */
    protected function askOptions()
    {
        foreach ($this->getOptions() as $option) {
            if ($option[0] == 'force') {
                continue;
            }

            $defVal = $this->input->getOption($option[0]);
            $val = $this->ask($option[3].'{'.$defVal.'}', $defVal);
            $this->input->setOption($option[0], $val);
        }
    }

    private function getConfig()
    {
        $config = [];
        foreach ($this->getOptions() as $option) {
            $config = array_add($config, $option[0], $this->input->getOption($option[0]));
        }

        $config = Installer::configDBConnection($config, 'DB_DRIVER', 'DB_DATABASE');

        return $config;
    }

    private function createDBConnection()
    {
        try {
            $config = [];
            foreach ($this->DBConfigs as $key => $value) {
                $config = array_add($config, $key, $this->input->getOption($value));
            }
            $config = Installer::configDBConnection($config, 'driver', 'database');

            return Installer::createDBConnection($config);
        } catch (InstallDatabaseException $e) {
            $this->error($e->GetMessage());

            return false;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        $defaults = Installer::getDefaultEnvironment();

        return [
            ['DB_DRIVER', 'driver', InputOption::VALUE_OPTIONAL, 'Database driver', array_get($defaults, 'DB_DRIVER')],
            ['DB_HOST', 'host', InputOption::VALUE_OPTIONAL, 'Database host', array_get($defaults, 'DB_HOST')],
            ['DB_DATABASE', 'db', InputOption::VALUE_OPTIONAL, 'Database name', array_get($defaults, 'DB_DATABASE')],
            ['DB_USERNAME', 'u', InputOption::VALUE_OPTIONAL, 'Database username', array_get($defaults, 'DB_USERNAME')],
            ['DB_PASSWORD', 'p', InputOption::VALUE_NONE, 'Database password'],
            ['DB_PREFIX', 'pr', InputOption::VALUE_NONE, 'Database prefix'],
            [
                'CACHE_DRIVER',
                'cache',
                InputOption::VALUE_OPTIONAL,
                'Cache driver [file|redis]',
                array_get($defaults, 'CACHE_DRIVER'),
            ],
            [
                'SESSION_DRIVER',
                'session',
                InputOption::VALUE_OPTIONAL,
                'Session driver [file|database]',
                array_get($defaults, 'SESSION_DRIVER'),
            ],
            [
                'APP_ENV',
                'env',
                InputOption::VALUE_OPTIONAL,
                'Application Environmet [local|production]',
                array_get($defaults, 'APP_ENV'),
            ],
            [
                'APP_DEBUG',
                'debug',
                InputOption::VALUE_OPTIONAL,
                'Application Debug [true|false]',
                array_get($defaults, 'APP_DEBUG'),
            ],
            ['APP_URL', 'url', InputOption::VALUE_OPTIONAL, 'Application host', array_get($defaults, 'APP_URL')],
            [
                'ADMIN_DIR_NAME',
                'dir',
                InputOption::VALUE_OPTIONAL,
                'Admin directory name',
                array_get($defaults, 'ADMIN_DIR_NAME'),
            ],
            ['force', null, InputOption::VALUE_OPTIONAL, 'Force the operation to run when in production.'],
        ];
    }
}
