<?php namespace KodiCMS\CMS\Console\Commands;

use ModuleLoader;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class ModulesList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cms:modules:list';

	/**
	 * The table headers for the command.
	 *
	 * @var array
	 */
	protected $headers = [
		'Name', ''
	];

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$modules = [];

		foreach(ModuleLoader::getRegisteredModules() as $module)
		{
			$modules[] = [
				$module->getName() . ' [' . get_class($module) .']',
				''
			];
			$modules[] = new TableSeparator;
			foreach($module->toArray() as $key => $value)
			{
				$modules[] = [
					$key,
					stripslashes(json_encode($value))
				];
			}

			$modules[] = new TableSeparator;
		}

		$this->table($this->headers, $modules);
	}
}
