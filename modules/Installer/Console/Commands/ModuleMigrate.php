<?php namespace KodiCMS\Installer\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
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
		$this->output->writeln('<info>Installing KodiCMS modules...</info>');
		$installer = new ModuleInstaller(App::make('module.loader')->getRegisteredModules());

		$installer->cleanOutputMessages();
		$installer->resetModules();
		$installer->migrateModules();

		foreach ($installer->getOutputMessages() as $message) {
			$this->output->writeln($message);
		}
	}
}
