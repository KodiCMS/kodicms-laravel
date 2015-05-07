<?php namespace KodiCMS\Installer\Console\Commands;

use Illuminate\Console\Command;
use ModuleLoader;
use KodiCMS\Installer\Support\ModuleInstaller;

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
		$installer = new ModuleInstaller(ModuleLoader::getRegisteredModules());

		$installer->cleanOutputMessages();
		$installer->resetModules();
		$installer->migrateModules();

		foreach ($installer->getOutputMessages() as $message) {
			$this->output->writeln($message);
		}
	}
}
