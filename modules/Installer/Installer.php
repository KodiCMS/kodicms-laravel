<?php

namespace KodiCMS\Installer;

use DB;
use CMS;
use Lang;
use Config;
use Artisan;
use Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\Store as SessionStore;
use KodiCMS\Installer\Support\ModulesInstaller;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;
use KodiCMS\Installer\Exceptions\InstallValidationException;

class Installer
{
    const POST_DATA_KEY = 'installer::data';
    const POST_DATABASE_KEY = 'installer::data';

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var SessionStore
     */
    protected $session;

    /**
     * @var ModulesInstaller
     */
    protected $installer;

    /**
     * @var Validator
     */
    protected $validation;

    /**
     * @var DatabaseManager
     */
    protected $connection;

    /**
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        $this->session = app('session');
        $this->files = $files;

        // TODO доработать
        event('installer.beforeInstall');
    }

    /**
     * @return array
     */
    public function getDefaultEnvironment()
    {
        return [
            'APP_ENV'        => env('APP_ENV', 'local'),
            'APP_DEBUG'      => config('cms.debug', true),
            'APP_KEY'        => str_random(32),
            'DB_DRIVER'      => env('DB_DRIVER', 'mysql'),
            'DB_HOST'        => env('DB_HOST', 'localhost'),
            'DB_DATABASE'    => env('DB_DATABASE', 'homestead'),
            'DB_USERNAME'    => env('DB_USERNAME', 'homestead'),
            'DB_PASSWORD'    => env('DB_PASSWORD', ''),
            'DB_PREFIX'      => env('DB_PREFIX', ''),
            'CACHE_DRIVER'   => env('CACHE_DRIVER', 'file'),
            'SESSION_DRIVER' => env('SESSION_DRIVER', 'file'),
            'QUEUE_DRIVER'   => env('QUEUE_DRIVER', 'sync'),
            'APP_URL'        => config('cms.url', 'http://localhost'),
            'ADMIN_DIR_NAME' => env('ADMIN_DIR_NAME', 'backend'),
        ];
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'title'             => CMS::NAME,
            'username'          => 'admin',
            'email'             => 'admin@yoursite.com',
            'generate_password' => false,
            'timezone'          => date_default_timezone_get(),
            'date_format'       => 'd F Y',
            'locale'            => Lang::getLocale(),
        ];
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return array_merge($this->getDefaultParameters(), $this->session->get(static::POST_DATA_KEY, []));
    }

    /**
     * @return array
     */
    public function getDatabaseParameters()
    {
        return $this->session->get(static::POST_DATABASE_KEY, []);
    }

    /**
     * @return array
     */
    public function getAvailableCacheTypes()
    {
        return ['file'];
    }

    /**
     * @return array
     */
    public function getAvailableSessionTypes()
    {
        return ['file', 'database'];
    }

    /**
     * @return array
     */
    public function getAvailableDatabaseDrivers()
    {
        return ['mysql', 'sqlite'];
    }

    /**
     * @param array $config
     * @param array $databaseConfig
     *
     * @return bool
     * @throws InstallException
     */
    public function install(array $config, array $databaseConfig)
    {
        if (isset($config['password_generate'])) {
            $config['password_field'] = str_random();
        }

        date_default_timezone_set($config['timezone']);

        $this->session->set(static::POST_DATA_KEY, $config);
        $this->session->set(static::POST_DATABASE_KEY, $databaseConfig);

        $this->validation = $this->checkPostData($config);

        $this->connection = $this->createDBConnection($databaseConfig);

        $this->createEnvironmentFile($config);

        return $config;
    }

    /**
     * Создание коннекта к БД.
     *
     * @param array $config [driver,host,database,username,password]
     *
     * @return DatabaseManager
     * @throws InstallDatabaseException
     */
    public function createDBConnection(array $config)
    {
        // Сбрасываем подключение к БД
        DB::purge();

        $driver = $config['driver'];
        if ($driver == 'sqlite') {
            if (is_dir(dirname($config['database'])) AND !file_exists($config['database'])) {
                @touch($config['database']);
            }        }
        unset($config['driver']);

        // Обновляем данные подключения к БД
        foreach ($config as $key => $value) {
            Config::set("database.connections.{$driver}.{$key}", $value);
        }
        Config::set("database.default", $driver);

        try {
            return DB::connection();
        } catch (\PDOException $e) {
            throw new InstallDatabaseException(trans('installer::core.messages.database_connection_failed'));
        }
    }

    /**
     * Очистка базы.
     */
    public function resetDatabase()
    {
        $tables = DB::connection()->getPdo()->query('SHOW FULL TABLES')->fetchAll();

        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        } else if (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF');
        }

        foreach ($tables as $table) {
            Schema::dropIfExists($table[0]);
        }

        if (Config::get('database.default') == 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        } else if (Config::get('database.default') == 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON');
        }
    }

    /**
     * Проверка данных формы.
     *
     * @param array $data
     *
     * @return Validator
     * @throws InstallValidationException
     */
    protected function checkPostData(array $data)
    {
        $data['directory'] = true;

        $validator = Validator::make($data, [
            'ADMIN_DIR_NAME'        => 'required',
            'USERNAME'              => 'required',
            'EMAIL'                 => 'required|email',
            'PASSWORD'              => 'required|confirmed',
            'PASSWORD_CONFIRMATION' => 'required',
        ]);

        if ($validator->fails()) {
            $e = new InstallValidationException;
            $e->setValidator($validator);

            throw $e;
        }

        return $validator;
    }

    /**
     * TODO: реализовать
     * Используется для установки данных из модулей.
     *
     * Метод проходится по модулям, ищет в них файл install.php, если существует
     * запускает его и передает массив $config
     */
    protected function installModules()
    {
        Artisan::call('modules:install');
    }

    protected function databaseMigrate()
    {
        Artisan::call('modules:migrate');
    }

    protected function databaseSeed()
    {
        Artisan::call('modules:seed');
    }

    /**
     * Создание конфиг файла.
     *
     * @param array $config
     *
     * @throws Installer_Exception
     * @return bool
     */
    public function createEnvironmentFile(array $config)
    {
        return $this->buildEnvFile($config);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/Console/Commands/stubs/env.stub';
    }

    /**
     * @return string
     */
    protected function getEnvPath()
    {
        return base_path(app()->environmentFile());
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
     * @param $config array
     *
     * @return string
     */
    protected function buildEnvFile(array $config)
    {
        $path = $this->getEnvPath();
        $stub = $this->files->get($this->getStub());

        $options = [];
        foreach ($this->getDefaultEnvironment() as $key => $default) {
            $value = isset($config[$key]) ? $config[$key] : $default;
            $options['{{'.$key.'}}'] = $value;
        }

        $stub = str_replace(array_keys($options), array_values($options), $stub);

        $this->makeDirectory($path);
        if ($this->files->put($path, $stub)) {
            return true;
        }

        return false;
    }

    protected function reset()
    {
        $this->installer->resetModules();

        if (is_file(app()->environmentFile())) {
            unlink(app()->environmentFile());
        }
    }
}
