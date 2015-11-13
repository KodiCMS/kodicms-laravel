<?php

namespace KodiCMS\Installer;

use DB;
use CMS;
use Lang;
use Config;
use Artisan;
use Validator;
use ModulesLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Database\DatabaseManager;
use Illuminate\Session\Store as SessionStore;
use KodiCMS\Installer\Support\ModulesInstaller;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;
use KodiCMS\Installer\Exceptions\InstallValidationException;
use KodiCMS\Users\Model\User;

class Installer
{
    const POST_DATA_KEY = 'installer::data';
    const POST_DATABASE_KEY = 'installer::database';

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
            'site_title'        => CMS::NAME,
            'username'          => 'admin',
            'email'             => 'admin@yoursite.com',
            'generate_password' => false,
            'timezone'          => date_default_timezone_get(),
            'date_format'       => 'd F Y',
            'locale'            => Lang::getLocale(),
            'admin_dir_name'    => 'backend',
            'cache_driver'      => 'file',
            'session_driver'    => 'file',
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
     * @return array
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

        $databaseConfig = $this->configDBConnection($databaseConfig, 'driver', 'database');

        $this->connection = $this->createDBConnection($databaseConfig);

        foreach ($databaseConfig as $key => $value) {
            $config['db_'.$key] = $value;
        }

        $this->createEnvironmentFile($config);

        $this->databaseDrop();

        $this->initModules();

        $this->databaseMigrate();

        $this->databaseSeed();

        $this->createAdmin($config);

        return $config;
    }

    /**
     * @param array $config
     * @param string $opt_driver
     * @param string $opt_database
     *
     * @return array
     */
    public function configDBConnection($config, $opt_driver, $opt_database)
    {
        if (array_get($config, $opt_driver) == 'sqlite') {
            $database = array_get($config, $opt_database);
            list($dirname) = array_values(pathinfo($database));
            if (empty($dirname) or $dirname == '.' or $dirname == '..') {
                $database = storage_path().DIRECTORY_SEPARATOR.$database.'.sqlite';
            }
            array_set($config, $opt_database, $database);
        }

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
            if (is_dir(dirname($config['database'])) and ! file_exists($config['database'])) {
                @touch($config['database']);
            }
        }
        unset($config['driver']);

        // Обновляем данные подключения к БД
        foreach ($config as $key => $value) {
            Config::set("database.connections.{$driver}.{$key}", $value);
        }
        Config::set('database.default', $driver);

        try {
            return DB::connection();
        } catch (\PDOException $e) {
            throw new InstallDatabaseException(trans('installer::core.messages.database_connection_failed'));
        }
    }

    /**
     * Иницилизация модулей.
     */
    public function initModules()
    {
        foreach (ModulesLoader::getRegisteredModules() as $module) {
            app()->call([$module, 'loadRoutes'], [app('router')]);
        }

        foreach (ModulesLoader::getRegisteredModules() as $module) {
            foreach ($module->loadConfig() as $group => $data) {
                Config::set($group, $data);
            }
        }
    }

    /**
     * Создание администратора.
     */
    public function createAdmin(array $config)
    {
        // Delete seeder admin users
        User::where('email', 'like', 'admin%@site.com')->delete();

        $user = User::create([
            'email'    => array_get($config, 'email'),
            'password' => array_get($config, 'password'),
            'username' => array_get($config, 'username'),
            'locale'   => array_get($config, 'locale', 'en'),
        ]);
        $user->roles()->sync([1, 2, 3]);
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

        $validator = Validator::make(array_change_key_case($data, CASE_LOWER), array_change_key_case([
            'ADMIN_DIR_NAME'        => 'required',
            'USERNAME'              => 'required',
            'EMAIL'                 => 'required|email',
            'PASSWORD'              => 'required|confirmed',
            'PASSWORD_CONFIRMATION' => 'required',
        ], CASE_LOWER));

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

    protected function databaseDrop()
    {
        Artisan::call('db:clear', ['--force' => true]);
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
        return $this->buildEnvFile(array_change_key_case($config, CASE_UPPER));
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
