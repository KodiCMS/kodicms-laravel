<?php namespace KodiCMS\CMS\Console\Commands;

use CMS;
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
	 * @param Filesystem $files
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
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

		$langDirectory = CMS::backendResourcesPath() . 'js' . DIRECTORY_SEPARATOR . 'locale';

		if(!$files->exists($langDirectory))
		{
			$files->makeDirectory($langDirectory, 0755, TRUE);
		}

		$this->output->progressStart(count($data));

		foreach($data as $locale => $translates)
		{
			$data = json_encode($translates);
			$file = $langDirectory . DIRECTORY_SEPARATOR . $locale . '.json';
			$files->put($file, $data);

			$this->output->progressAdvance();
			$this->line("<info>File [{$file}]</info> for locale: [{$locale}] created");
		}

		$this->output->progressFinish();
	}

}
