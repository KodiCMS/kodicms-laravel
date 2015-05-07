<?php namespace KodiCMS\CMS\Console\Commands;

use ModuleLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class GenerateScriptTranslates extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cms:generate:translate:js';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(Filesystem $files)
	{
		$data = [];

		foreach(ModuleLoader::getRegisteredModules() as $module)
		{
			if(!is_dir($module->getLocalePath())) continue;

			foreach($files->directories($module->getLocalePath()) as $localeDir)
			{
				$locale = basename($localeDir);
				foreach($files->allFiles($localeDir) as $localeFile)
				{
					$data[$locale][strtolower($module->getName())][basename($localeFile, '.php')] = $files->getRequire($localeFile->getRealPath());
				}
			}
		}

		$langDirectory = \CMS::backendResourcesPath() . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR . 'locale';

		if(!$files->exists($langDirectory))
		{
			$files->makeDirectory($langDirectory, 0755, TRUE);
		}

		foreach($data as $locale => $translates)
		{
			$data = json_encode($translates);
			$files->put($langDirectory . DIRECTORY_SEPARATOR . $locale . '.json', $data);
		}
	}

}
