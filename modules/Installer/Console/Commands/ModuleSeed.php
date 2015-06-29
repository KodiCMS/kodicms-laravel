<?php namespace KodiCMS\Installer\Console\Commands;

use ModuleLoader;
use Illuminate\Console\Command;
use KodiCMS\Installer\Support\ModuleInstaller;

class ModuleSeed extends Command
{

	/**
	 * The console command name.
	 */
	protected $name = 'cms:modules:seed';


	/**
	 * Execute the console command.
	 */
	public function fire()
	{
		$this->output->writeln('<info>Seeding KodiCMS modules...</info>');
		$installer = new ModuleInstaller(ModuleLoader::getRegisteredModules());

		$installer->cleanOutputMessages();
		$installer->seedModules();

		foreach ($installer->getOutputMessages() as $message)
		{
			$this->output->writeln($message);
		}
	}
}
