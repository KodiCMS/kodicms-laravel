<?php namespace KodiCMS\Installer\Console\Commands;

use App;
use Installer;
use EnvironmentTester;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use KodiCMS\Installer\Exceptions\InstallException;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;

class InstallCommand extends GeneratorCommand
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:install';

	/**
	 * @var array
	 */
	protected $env = [];

	/**
	 * Configs DB
	 */
	protected $DBConfigs = [
		'host' => 'DB_HOST',
		'database' => 'DB_DATABASE',
		'username' => 'DB_USERNAME',
		'password' => 'DB_PASSWORD',
		'prefix' => 'DB_PREFIX'
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

		if (!empty($optional))
		{
			$this->info('Optional tests');
			$this->table($this->testHeaders, $optional);
		}

		if ($failed)
		{
			throw new InstallException('Environment test failed');
		}

		if (App::installed())
		{
			$this->error('.env file already exists!');

			if (!$this->confirm('Do you want rewrite file env?'))
			{
				return $this->error("Installation is aborted.");
			}
		}

		$db = $this->createDBConnection();

		while (!$db && $this->confirm('Do you want enter settings?'))
		{
			$this->askOptions();
			$db = $this->createDBConnection();
		}

		if (!$db)
		{
			return $this->error("Installation is aborted.");
		}

		if (Installer::createEnvironmentFile($this->getConfig()))
		{
			$this->info('.env file created successfully.');
		}

//		if ($this->confirm("Clear database? [yes/no]"))
//		{
//			Installer::resetDatabase($this->input->getOption("DB_DATABASE"));
//			$this->info("Database cleaned");
//		}

		if ($this->confirm('Migrate database?'))
		{
			$this->migrate();
			if ($this->confirm('Install seed data?'))
			{
				$this->seed();
			}
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
		return __DIR__ . '/stubs/env.stub';
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
			['DB_PREFIX', 'pr', InputOption::VALUE_OPTIONAL, 'Database prefix', array_get($defaults, 'DB_PREFIX')],
			['CACHE_DRIVER', 'cache', InputOption::VALUE_OPTIONAL, 'Cache driver [file|redis]', array_get($defaults, 'CACHE_DRIVER')],
			['SESSION_DRIVER', 'session', InputOption::VALUE_OPTIONAL, 'Session driver [file|database]', array_get($defaults, 'SESSION_DRIVER')],
			['APP_ENV', 'env', InputOption::VALUE_OPTIONAL, 'Application Environmet [local|production]', array_get($defaults, 'APP_ENV')],
			['APP_DEBUG', 'debug', InputOption::VALUE_OPTIONAL, 'Application Debug [true|false]', array_get($defaults, 'APP_DEBUG')],
			['APP_URL', 'url', InputOption::VALUE_OPTIONAL, 'Application host', array_get($defaults, 'APP_URL')],
			['ADMIN_DIR_NAME', 'dir', InputOption::VALUE_OPTIONAL, 'Admin directory name', array_get($defaults, 'ADMIN_DIR_NAME')]
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
	 * Ask options
	 */
	protected function askOptions()
	{
		foreach ($this->getOptions() as $option)
		{
			$defVal = $this->input->getOption($option[0]);
			$val = $this->ask($option[3] . "{" . $defVal . "}", $defVal);
			$this->input->setOption($option[0], $val);
		}
	}

	private function getConfig()
	{
		$config = [];
		foreach ($this->getOptions() as $option)
		{
			$config = array_add($config, $option[0], $this->input->getOption($option[0]));
		}

		return $config;
	}

	private function createDBConnection()
	{
		try
		{
			$config = [];
			foreach ($this->DBConfigs as $key => $value)
			{
				$config = array_add($config, $key, $this->input->getOption($value));
			}

			return Installer::createDBConnection($config);
		}
		catch (InstallDatabaseException $e)
		{
			$this->error($e->GetMessage());

			return false;
		}
	}

}
