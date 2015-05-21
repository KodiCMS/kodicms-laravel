<?php namespace KodiCMS\Installer\Console\Commands;

use DB;
use Config;
use KodiCMS\Installer\Installer;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class Install extends GeneratorCommand
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
	 * @return array
	 */
	protected function getEnvironment()
	{
		if (empty($this->env))
		{
			foreach (Installer::getDefaultEnvironment() as $key => $default)
			{
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
		return __DIR__.'/stubs/env.stub';
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
		if ($this->files->exists($path = $this->getEnvPath()))
		{
			return $this->error('.env file already exists!');
		}

        if($this->isDefaultOptions() && $this->confirm('Do you want enter the options? [yes:no]'))
        {
            $this->comment(PHP_EOL.'Press enter to set default value'.PHP_EOL);

            foreach($this->getOptions() as $o)
            {
                $oVal =  $this->ask('Set '.$o[0].'('.$o[4].'): ',$o[4]);
                $this->input->setOption($o[0],$oVal);
            }
        }

		$this->makeDirectory($path);


		if($this->files->put($path, $this->buildEnvFile()))
		{
			$this->info('.env file created successfully.');
			$this->migrate();
			$this->seed();
		}
	}

	/**
	 * Миграция данных
	 */
	public function migrate()
	{
		// Сбрасываем подключение к БД
		DB::purge();

		$configs = [
			'host' => 'DB_HOST',
			'database' => 'DB_DATABASE',
			'username' => 'DB_USERNAME',
			'password' => 'DB_PASSWORD'
		];

		// Обновляем данные подключения к БД
		foreach($configs as $key => $env)
		{
			Config::set("database.connections.mysql.{$key}", array_get($this->getEnvironment(), $env));
		}

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
		foreach(Installer::getDefaultEnvironment() as $key => $default)
		{
			$value = $this->input->hasOption($key) ? $this->input->getOption($key) : $default;
			$options['{{' . $key . '}}'] =  $value;
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
     * Chehk is default options
     */
    public function isDefaultOptions()
    {
        foreach($this->getOptions() as $dOption)
        {
            if($this->option($dOption[0])!=$dOption[4])
            {
                return false;
            }
        }

        return true;
    }


}
