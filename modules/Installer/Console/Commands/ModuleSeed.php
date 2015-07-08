<?php namespace KodiCMS\Installer\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use KodiCMS\Installer\Support\ModulesInstaller;

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
		$installer = new ModulesInstaller(ModulesLoader::getRegisteredModules());

		$installer->cleanOutputMessages();
		$installer->seedModules();

		foreach ($installer->getOutputMessages() as $message)
		{
			$this->output->writeln($message);
		}
	}
}
