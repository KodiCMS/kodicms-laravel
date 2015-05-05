<?php namespace KodiCMS\Installer;

use DB;
use CMS;
use Lang;
use Validator;
use Artisan;
use Illuminate\Session\Store as SessionStore;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallValidationException;

class Installer {

	/**
	 * @var SessionStore
	 */
	protected $session;

	public function __construct(SessionStore $session)
	{
		$this->session = $session;

		$this->_include_module_observers();
	}

	/**
	 * @return array
	 */
	public function getDefaultParameters()
	{
		return [
			'db_server' => 'localhost',
			'db_port' => 3306,
			'db_user' => 'root',
			'db_name' => 'kodicms',
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
	public function getAvailableCacheTypes()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public function getAvailableSessionTypes()
	{
		return [];
	}

	/**
	 * @return array
	 */
	public function getAvailableDatabaseDrivers()
	{
		return [];
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

		if (isset($post['db_port']))
		{
			$post['db_port'] = (int) $post['db_port'];
		}

		date_default_timezone_set($post['timezone']);

		$this->session->set('install_data', $post);

		$this->validation = $this->_valid($post);

		try
		{
			$this->_db_instance = $this->connect_to_db($post);
		}
		catch (Database_Exception $exc)
		{
			$validation = FALSE;
			switch ($exc->getCode())
			{
				case 1049:
					$this->_validation->error('db_name', 'incorrect');
					$validation = TRUE;
					break;
				case 2:
					$this->_validation
						->error('db_server', 'incorrect')
						->error('db_user', 'incorrect')
						->error('db_password', 'incorrect');

					$validation = TRUE;
					break;
			}

			if ($validation === TRUE)
			{
				throw new Validation_Exception($this->_validation, $exc->getMessage(), NULL, $exc->getCode());
			}
			else
			{
				throw new Database_Exception($exc->getMessage(), NULL, $exc->getCode());
			}
		}

		Database::$default = 'install';

		Observer::notify('before_install', $post, $this->_validation);

		if (isset($post['empty_database']))
		{
			$this->_reset();
		}

		define('TABLE_PREFIX_TMP', Arr::get($post, 'db_table_prefix', ''));

		$this->_import_shema($post);
		$this->_import_dump($post);
		$this->_install_modules($post);

		Observer::notify('install', $post);

		$this->_create_site_config($post);
		$this->_create_config_file($post);
		return TRUE;
	}

	protected function _include_module_observers()
	{
		if (!is_dir(CMS_MODPATH))
		{
			return;
		}

		// Create a new directory iterator
		$path = new DirectoryIterator(CMS_MODPATH);

		foreach ($path as $dir)
		{
			if ($dir->isDot())
			{
				continue;
			}

			$file_name = CMS_MODPATH . $dir->getBasename() . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'observers' . EXT;
			if (file_exists($file_name))
			{
				include $file_name;
			}
		}
	}

	/**
	 * Создание коннекта к БД
	 *
	 * @param array $post
	 * @return Database
	 * @throws Validation_Exception
	 */
	public function connect_to_db(array $post)
	{
		define('DB_DRIVER', $post['db_driver']);

		$config = Kohana::$config->load('database');

		$charset = 'utf8';
		$type = $post['db_driver'];

		switch ($type)
		{
			case 'mysql':
				$connection = [
					'hostname'	 => $post['db_server'] . ':' . $post['db_port'],
					'database'	 => $post['db_name'],
					'username'	 => $post['db_user'],
					'password'	 => $post['db_password'],
					'persistent' => FALSE,
				];
				break;
			case 'mysqli':
				$connection = [
					'hostname'	 => $post['db_server'],
					'port'		 => $post['db_port'],
					'database'	 => $post['db_name'],
					'username'	 => $post['db_user'],
					'password'	 => $post['db_password'],
					'persistent' => FALSE,
				];
				break;
			case 'pdo':
			case 'pdo::mysql':
				$connection = [
					'dsn'        => 'mysql:host='.$post['db_server'].';port='.$post['db_port'].';dbname='.$post['db_name'],
					'username'   => $post['db_user'],
					'password'   => $post['db_password'],
					'persistent' => FALSE,
				];

				$type = 'pdo';
				break;
			case 'pdo::sqlite':
				$connection = [
					'dsn'        => 'sqlite:' . CMSPATH . 'db' . DIRECTORY_SEPARATOR .  '.' . $post['db_name'] . '.sqlite',
					'username'   => NULL,
					'password'   => NULL,
					'persistent' => FALSE,
				];

				$type = 'pdo_sqlite';
				$charset = NULL;
				break;
		}

		$config->set('install', [
			'type' => $type,
			'connection' => $connection,
			'table_prefix' => $post['db_table_prefix'],
			'charset' => $charset,
			'caching' => FALSE,
			'profiling' => TRUE,
			'driver' => $post['db_driver']
		]);

		$db = Database::instance('install');
		$db->connect();

		return $db;
	}

	/**
	 * Проверка данных формы
	 *
	 * @param array $data
	 * @return Validator
	 * @throws InstallValidationException
	 */
	protected function _valid(array $data)
	{
		$data['directory'] = TRUE;

		$validator = Validator::make($data, [
			'admin_dir_name' => 'required',
			'username' => 'required',
			'email' => 'required|email',
			'cache_type' => 'required',
			'session_type' => 'required'
		]);

		$validator->sometimes(['db_server', 'db_user', 'db_name'], 'required', function($input)
		{
			return !empty($input->db_driver);
		});

		$validator->sometimes(('password_field', 'password_confirm'], 'required', function($input)
		{
			return !empty($input->db_driver);
		});



		$validation = Validation::factory($data)
			->rule('admin_dir_name', 'not_empty')
			->rule('username', 'not_empty')
			->rule('email', 'not_empty')
			->rule('email', 'email')
			->rule('cache_type', 'not_empty')
			->rule('cache_type', 'in_array', [':value', array_keys($this->cache_types())])
			->rule('session_type', 'not_empty')
			->rule('session_type', 'in_array', [':value', array_keys($this->session_types())])
			->rule('db_driver', 'in_array', [':value', array_keys($this->database_drivers())])
			->label('db_name', __('Database name'))
			->label('admin_dir_name', __('Admin dir name'))
			->label('username', __('Administrator username'))
			->label('email', __('Administrator email'))
			->label('password_field', __('Password'))
			->label('cache_type', __('Cache type'))
			->label('session_type', __('Session type'));

		switch ($data['db_driver'])
		{
			default:
				$validation
					->rule('db_server', 'not_empty')
					->rule('db_user', 'not_empty')
					->rule('db_name', 'not_empty')
					->label('db_server', __('Database server'))
					->label('db_user', __('Database user'))
					->label('db_password', __('Database password'));
				break;
		}

		if (!isset($data['password_generate']))
		{
			$validation
				->rule('password_field', 'min_length', [':value', Kohana::$config->load('auth')->get('password_length')])
				->rule('password_field', 'not_empty')
				->rule('password_confirm', 'matches', [':validation', ':field', 'password_field'])
				->label('password_confirm', __('Confirm Password'));
		}

		if (!$validation->check())
		{
			throw new Validation_Exception($validation);
		}

		return $validation;
	}

	/**
	 * Используется для установки данных из модулей
	 *
	 * Метод проходится по модулям, ищет в них файл install.php, если существует
	 * запускает его и передает массив $post
	 */
	protected function installModules()
	{
		Artisan::call('cms:module:install');
	}

	protected function databaseMigrate()
	{
		Artisan::call('cms:module:migrate');
	}

	protected function databaseSeed(array $post)
	{
		$this->session->put('install_data', [
			'__EMAIL__'				=> array_get($post, 'email'),
			'__USERNAME__'			=> array_get($post, 'username'),
			'__ADMIN_PASSWORD__'	=> array_get($post, 'password_field'),
			'__LANG__'				=> config('app', 'locale')
		]);

		Artisan::call('cms:module:seed');
	}

	/**
	 * Запись в БД данных в таблицу config
	 * Значения по умолчанию устанавливаются в конфиг файле `installer`
	 *
	 * @param array $post
	 */
	protected function _create_site_config(array $post)
	{
		$config_values = $this->_config->get('default_config', []);

		$config_values['site']['title'] = Arr::get($post, 'site_name');
		$config_values['site']['date_format'] = Arr::get($post, 'date_format');
		$config_values['site']['default_locale'] = Arr::get($post, 'locale');
		$config_values['email']['default'] = Arr::get($post, 'email');

		$insert = DB::insert('config', ['group_name', 'config_key', 'config_value']);
		foreach ($config_values as $group => $data)
		{
			foreach ($data as $key => $config)
			{
				$insert->values([$group, $key, Kohana::serialize($config)]);
			}
		}

		$insert->execute($this->_db_instance);
	}



	/**
	 * Создание конфиг файла
	 *
	 * @param array $post
	 * @throws Installer_Exception
	 */
	protected function _create_config_file(array $post)
	{
		$tpl_file = INSTALL_DATA . 'config.tpl';

		if (!file_exists($tpl_file))
		{
			throw new Installer_Exception('Config template file :file not found!', [
				':file' => $tpl_file
			]);
		}

		// Insert settings to config.php		
		$tpl_content = file_get_contents($tpl_file);

		$type = $post['db_driver'];
		switch ($type)
		{
			case 'pdo':
			case 'pdo::mysql':
			case 'pdo::sqlite':
				$type = 'pdo';
				break;
		}

		$repl = [
			'__DB_TYPE__'			=> $type,
			'__DB_DRIVER__'			=> $post['db_driver'],
			'__DB_SERVER__'			=> $post['db_server'],
			'__DB_PORT__'			=> $post['db_port'],
			'__DB_NAME__'			=> $post['db_name'],
			'__DB_USER__'			=> $post['db_user'],
			'__DB_PASS__'			=> $post['db_password'],
			'__TABLE_PREFIX__'		=> $post['db_table_prefix'],
			'__URL_SUFFIX__'		=> $post['url_suffix'],
			'__ADMIN_DIR_NAME__'	=> $post['admin_dir_name'],
			'__TIMEZONE__'			=> $post['timezone'],
			'__COOKIE_SALT__'		=> Text::random('alnum', 16),
			'__CACHE_TYPE__'		=> $post['cache_type'],
			'__SESSION_TYPE__'		=> $post['session_type']
		];

		$tpl_content = str_replace(
			array_keys($repl), array_values($repl), $tpl_content
		);

		$this->_write_config_to_file($tpl_content);
	}

	/**
	 * Очистка указанной БД от записей
	 */
	protected function _reset()
	{
		$this->_db_instance->disable_foreign_key_checks();

		$tables = DB::query(Database::SELECT, 'SHOW TABLES')->execute($this->_db_instance);

		foreach ($tables as $table)
		{
			$table = array_values($table);
			$table_name = $table[0];

			DB::query(null, 'DROP TABLE `:table_name`')->param(':table_name', DB::expr($table_name))->execute($this->_db_instance);
		}

		if (file_exists(CFGFATH) !== false)
		{
			$this->_write_config_to_file('');
		}

		$this->_db_instance->enable_foreign_key_checks();
	}

	/**
	 *
	 * @param string $content
	 * @return boolean
	 * @throws InstallException
	 */
	protected function _write_config_to_file($content)
	{
		if (!file_put_contents(CFGFATH, $content) !== false)
		{
			throw new InstallException('Can\'t write config.php file!');
		}

		return true;
	}
}