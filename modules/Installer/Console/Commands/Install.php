<?php namespace KodiCMS\Installer\Console\Commands;

use DB;
use Config;
use KodiCMS\Installer\Installer;
use Illuminate\Console\GeneratorCommand;
use League\Flysystem\Exception;
use Symfony\Component\Console\Input\InputOption;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;

class Install extends GeneratorCommand
{

    /**
     * DB configs
     */
    protected $configsDB = [
        'db_host' => 'DB_HOST',
        'db_database' => 'DB_DATABASE',
        'db_username' => 'DB_USERNAME',
        'db_password' => 'DB_PASSWORD'
    ];

    /**
     * The console command name.
     */
    protected $name = 'cms:install';

    /**
     * @var array
     */
    protected $env = [];

    /**
     * @return array
     */
    protected function getEnvironment()
    {
        if (empty($this->env)) {
            foreach (Installer::getDefaultEnvironment() as $key => $default) {
                $this->env[$key] = $this->input->hasOption($key) ? $this->input->getOption($key) : $default;
            }
        }

        return $this->env;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/env.stub';
    }

    /**
     * @return string
     */
    public function getEnvPath()
    {
        return base_path(app()->environmentFile());
    }

    /**
     * Execute the console command.
     */
    public function fire()
    {

        $createEnv = true;

        if ($this->files->exists($path = $this->getEnvPath())) {

            $this->error(".env file already exists.");

            if ($this->confirm("Do you want to overwrite the file? [yes:no]")) {

                $this->manualSetting();

            } else {

                if ( ! $this->confirm("Do you want to continue the installation with the current settings? [yes:no]")) {

                    $this->info('You have decided not to change anything!');
                    return false;

                }

                $createEnv = false;
                $this->setCurrentOptions();
                $this->SetDBConnection();

            }

        } else {

            if ($this->isDefaultOptions() && $this->confirm('Do you want enter the options? [yes:no]')) {
                $this->manualSetting();
            }

        }

        $this->makeDirectory($path);

        if (!$createEnv || $this->files->put($path, $this->buildEnvFile())) {

            $this->info(PHP_EOL.'.env file created successfully.'.PHP_EOL);
            $this->migrate();
            $this->seed();
        }
    }


    /**
     * Миграция данных
     */
    public function migrate()
    {
        $this->call('cms:modules:migrate');
    }

    /**
     * Сидирование данных
     */
    public function seed()
    {
        $this->call('cms:modules:seed');
    }

    /**
     * @return string
     */
    protected function buildEnvFile()
    {
        $stub = $this->files->get($this->getStub());

        $options = [];
        foreach (Installer::getDefaultEnvironment() as $key => $default) {
            $value = $this->input->hasOption($key) ? $this->input->getOption($key) : $default;
            $options['{{' . $key . '}}'] = $value;
        }

        $stub = str_replace(
            array_keys($options), array_values($options), $stub
        );

        return $stub;
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        $defaults = Installer::getDefaultEnvironment();

        return [
            ['DB_HOST', 'host', InputOption::VALUE_OPTIONAL, "Database host", array_get($defaults, 'DB_HOST')],
            ['DB_DATABASE', 'db', InputOption::VALUE_OPTIONAL, 'Database name', array_get($defaults, 'DB_DATABASE')],
            ['DB_USERNAME', 'u', InputOption::VALUE_OPTIONAL, 'Database username', array_get($defaults, 'DB_USERNAME')],
            ['DB_PASSWORD', 'p', InputOption::VALUE_OPTIONAL, 'Database password', array_get($defaults, 'DB_PASSWORD')],
            [
                'CACHE_DRIVER',
                'cache',
                InputOption::VALUE_OPTIONAL,
                'Cache driver [file|redis]',
                array_get($defaults, 'CACHE_DRIVER')
            ],
            [
                'SESSION_DRIVER',
                'session',
                InputOption::VALUE_OPTIONAL,
                'Session driver [file|database]',
                array_get($defaults, 'SESSION_DRIVER')
            ],
            [
                'APP_ENV',
                'env',
                InputOption::VALUE_OPTIONAL,
                'Application Environmet [local|production]',
                array_get($defaults, 'APP_ENV')
            ],
            [
                'APP_DEBUG',
                'debug',
                InputOption::VALUE_OPTIONAL,
                'Application Debug [true|false]',
                array_get($defaults, 'APP_DEBUG')
            ],
            ['APP_URL', 'url', InputOption::VALUE_OPTIONAL, 'Application host', array_get($defaults, 'APP_URL')],
            [
                'ADMIN_DIR_NAME',
                'dir',
                InputOption::VALUE_OPTIONAL,
                'Admin directory name',
                array_get($defaults, 'ADMIN_DIR_NAME')
            ]
        ];
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
     * Cheсk is default options
     */
    private function isDefaultOptions()
    {
        foreach ($this->getOptions() as $dOption) {
            if ($this->option($dOption[0]) != $dOption[4]) {
                return false;
            }
        }

        return true;
    }


    /**
     * Manual settings
     */
    private function manualSetting()
    {
        $db = true;
        do {
            $this->askOptions($db);

            $db = $this->SetDBConnection();

        } while (!($db || !$this->confirm('Do you want repeat enter? [yes:no] ')));
    }

    /**
     * ask the option
     */
    private function askOptions($db)
    {

        $this->comment(PHP_EOL . 'Press enter to set default value' . PHP_EOL);

        foreach ($this->getOptions() as $option) {
            if (!$db && $this->input->hasOption($option[0])) {
                $defVal = $this->option($option[0]);
            } else {
                $defVal = $option[4];
            }

            $optionVal = $this->ask('Set ' . $option[0] . '(' . $defVal . '): ', $defVal);
            $this->input->setOption($option[0], $optionVal);
        }
    }

    /**
     * Set options from current .env
     */
    private function setCurrentOptions()
    {

        foreach ($this->getOptions() as $option) {
            $this->input->setOption($option[0], env($option[0],$option[4]));
        }

    }

    /**
     * CheckDbConnection
     */
    private function SetDBConnection()
    {
        $db = true;

        $config = [];

        foreach ($this->configsDB as $key => $option) {
            $config[$key] = $this->option($option);
        }

        try {
            Installer::createDatabaseConnection($config);
        } catch (InstallDatabaseException $e) {
            $db = false;
            $this->comment($e->getMessage());
            $this->error('Database connection failed !!!');
        }

        return $db;

    }
}
