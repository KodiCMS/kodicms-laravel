<?php namespace KodiCMS\ModulesLoader\Console\Commands;

use ModulesLoader;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\TableSeparator;

class ModulesListCommand extends Command {

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

		foreach(ModulesLoader::getRegisteredModules() as $module)
		{
			$modules[] = [
				$module->getName() . ' [' . get_class($module) .']',
				''
			];
			$modules[] = new TableSeparator;
			foreach($module->toArray() as $key => $value)
			{
				$modules[] = [
					studly_case($key),
					is_string($value) ? $value : stripslashes(json_encode($value))
				];
			}

			$modules[] = new TableSeparator;
		}

		$this->table($this->headers, $modules);
	}
}
