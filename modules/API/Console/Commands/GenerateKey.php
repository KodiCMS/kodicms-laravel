<?php namespace KodiCMS\API\Console\Commands;

use DatabaseConfig;
use KodiCMS\API\Model\ApiKey;
use Illuminate\Console\Command;

class GenerateKey extends Command
{
	/**
	 * The console command name.
	 */
	protected $name = 'api:generate_key';


	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		$this->output->writeln('<info>Generating KodiCMS API key...</info>');

		$key = ApiKey::generate();
		DatabaseConfig::set('cms', 'api_key', $key);

		if (!is_null($key))
		{
			$this->output->writeln("<info>New API key generated: {$key}</info>");
		}

		return $key;
	}
}
