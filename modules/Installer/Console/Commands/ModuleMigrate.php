<?php namespace KodiCMS\Installer\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use KodiCMS\Installer\Support\ModulesInstaller;
use Symfony\Component\Console\Input\InputOption;

class ModuleMigrate extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:modules:migrate';


	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		$this->output->writeln('<info>Migrating KodiCMS modules...</info>');
		$installer = new ModulesInstaller(ModulesLoader::getRegisteredModules());

		$installer->cleanOutputMessages();
		$installer->resetModules();
		$installer->migrateModules();

		foreach ($installer->getOutputMessages() as $message)
		{
			$this->output->writeln($message);
		}

		// Finally, if the "seed" option has been given, we will re-run the database
		// seed task to re-populate the database, which is convenient when adding
		// a migration and a seed at the same time, as it is only this command.
		if ($this->input->getOption('seed'))
		{
			$this->call('cms:modules:seed');
		}
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run.']
		];
	}
}
