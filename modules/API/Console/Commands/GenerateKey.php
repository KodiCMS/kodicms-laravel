<?php namespace KodiCMS\API\Console\Commands;

use Illuminate\Console\Command;
use KodiCMS\API\Model\ApiKey;

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

		$id = ApiKey::generateKey();

		if(!is_null($id)) {
			$this->output->writeln("<info>New API key generated: {$id}</info>");
		}
	}
}
