<?php namespace KodiCMS\CMS\Console\Commands;

use ModuleLoader;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;

class GenerateLocalePackage extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'cms:generate:locale';

	/**
	 * @var string
	 */
	protected $copiedLocale = 'ru';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(Filesystem $files)
	{
		$data = [];

		$newLocale = $this->input->getOption('locale');

		foreach(ModuleLoader::getRegisteredModules() as $module)
		{
			if(!is_dir($module->getLocalePath())) continue;

			foreach($files->directories($module->getLocalePath()) as $localeDir)
			{
				$locale = basename($localeDir);

				if($locale != $this->copiedLocale) continue;

				foreach($files->allFiles($localeDir) as $localeFile)
				{
					$data[strtolower($module->getName())][] = $localeFile->getRealPath();
				}
			}
		}

		$langDirectory = base_path('/resources/lang/packages');

		foreach($data as $namespace => $locales)
		{
			foreach($locales as $group => $file)
			{
				$filename = pathinfo($file, PATHINFO_FILENAME);

				$fileDir = $langDirectory . DIRECTORY_SEPARATOR . $newLocale . DIRECTORY_SEPARATOR .$namespace;
				if(!$files->exists($fileDir))
				{
					$files->makeDirectory($fileDir, 0755, TRUE);
				}

				$files->copy($file, $fileDir . DIRECTORY_SEPARATOR . $filename . '.php');
			}
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
			['locale', 'en', InputOption::VALUE_OPTIONAL, 'Locale lang package to be generated']
		];
	}
}
