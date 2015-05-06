<?php namespace KodiCMS\Installer;

use DB;
use CMS;
use Illuminate\Database\DatabaseManager;
use KodiCMS\Installer\Support\ModuleInstaller;
use Lang;
use Validator;
use Artisan;
use Config;
use Illuminate\Support\Str;
use Illuminate\Session\Store as SessionStore;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallValidationException;

class Installer {

	const SESSION_KEY = 'installer::data';

	/**
	 * @return array
	 */
	public static function getDefaultEnvironment()
	{
		return [
			'APP_ENV' => 'local',
			'APP_DEBUG' => 'true',
			'APP_KEY' => Str::random(32),
			'DB_HOST' => 'localhost',
			'DB_DATABASE' => 'homestead',
			'DB_USERNAME' => 'homestead',
			'DB_PASSWORD' => '',
			'CACHE_DRIVER' => 'file',
			'SESSION_DRIVER' => 'database',
			'QUEUE_DRIVER' => 'database',
			'APP_URL' => 'http://localhost',
			'ADMIN_DIR_NAME' => 'backend'
		];
	}

	/**
	 * @var SessionStore
	 */
	protected $session;

	/**
	 * @var ModuleInstaller
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

	public function __construct()
	{
		$this->session = app('session');

		// TODO доработать
		event('installer.beforeInstall');
	}

	/**
	 * @return array
	 */
	public function getDefaultParameters()
	{
		return [
			'db_host' => 'localhost',
			'db_username' => 'root',
			'db_database' => 'kodicms',
			'site_name' => CMS::NAME,
			'username' => 'admin',
			'email' => 'admin@yoursite.com',
			'admin_dir_name' => 'backend',
			'url_suffix' => '.html',
			'password_generate' => FALSE,
			'timezone' => date_default_timezone_get(),
			'date_format' => 'd F Y',
			'locale' => Lang::getLocale()
		];
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return array_merge($this->getDefaultParameters(), $this->session->get(static::SESSION_KEY, []));
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
		return ['mysql'];
	}

	/**
	 * @param array $post
	 * @return boolean
	 * @throws InstallException
	 */
	public function install(array $post)
	{
		if (empty($post))
		{
			throw new InstallException('No install data!');
		}

		if (isset($post['password_generate']))
		{
			$post['password_field'] = str_random();
		}

//		if (isset($post['admin_dir_name']))
//		{
//			$post['admin_dir_name'] = URL::title($post['admin_dir_name']);
//		}

		date_default_timezone_set($post['timezone']);

		$this->session->set(static::SESSION_KEY, $post);
		$this->validation = $this->checkPostData($post);
		$this->connection = $this->checkDatabaseConnection($post);


		if (isset($post['empty_database']))
		{
			$this->reset();
		}

//		$this->installModules();
		$this->createEnvironmentFile($post);
//		$this->databaseMigrate();
//		$this->databaseSeed($post);

		return $post;
	}

	/**
	 * Создание коннекта к БД
	 *
	 * @param array $post
	 * @return DatabaseManager
	 * @throws InstallDatabaseException
	 */
	public function checkDatabaseConnection(array $post)
	{
		// Сбрасываем подключение к БД
		DB::purge();

		$configs = [
			'host' => 'db_host',
			'database' => 'db_database',
			'username' => 'db_username',
			'password' => 'db_password'
		];

		// Обновляем данные подключения к БД
		foreach($configs as $key => $env)
		{
			Config::set("database.connections.mysql.{$key}", array_get($post, $env));
		}

		try
		{
			return DB::connection();
		}
		catch(\PDOException $e)
		{
			throw new InstallDatabaseException(trans('installer::core.messages.database_connection_failed'));
		}
	}

	/**
	 * Проверка данных формы
	 *
	 * @param array $data
	 * @return Validator
	 * @throws InstallValidationException
	 */
	protected function checkPostData(array $data)
	{
		$data['directory'] = TRUE;

		$validator = Validator::make($data, [
			'admin_dir_name' => 'required',
			'username' => 'required',
			'email' => 'required|email',
			'cache_type' => 'required',
			'session_type' => 'required',
			'password' => 'required|confirmed',
			'password_confirmation' => 'required',
			'db_host' => 'required',
			'db_database' => 'required',
			'db_username' => 'required'
		]);

		if (!$validator->fails())
		{
			throw new InstallValidationException($validator);
		}

		return $validator;
	}

	/**
	 * Используется для установки данных из модулей
	 *
	 * Метод проходится по модулям, ищет в них файл install.php, если существует
	 * запускает его и передает массив $post
	 */
	protected function installModules()
	{
		Artisan::call('cms:modules:install');
	}

	protected function databaseMigrate()
	{
		Artisan::call('cms:modules:migrate');
	}

	protected function databaseSeed(array $post)
	{
		// TODO: реализовать передачу в файлы сидов данные из инсталлятора
		Artisan::call('cms:modules:seed');
	}

	/**
	 * Создание конфиг файла
	 *
	 * @param array $post
	 * @throws Installer_Exception
	 */
	protected function createEnvironmentFile(array $post)
	{
		Artisan::call('cms:install', [
			'-host'			=> $post['db_host'],
			'-db'			=> $post['db_database'],
			'-u'			=> $post['db_username'],
			'-p'			=> $post['db_password'],
//			'-prefix'		=> $post['db_prefix'],
//			'-suffix'		=> $post['url_suffix'],
			'-url'			=> url('/'),
			'-dir'			=> $post['admin_dir_name']
		]);
	}

	protected function reset()
	{
		$$this->installer->resetModules();

		if(is_file(base_path('.env')))
		{
			unlink(base_path('.env'));
		}
	}
}