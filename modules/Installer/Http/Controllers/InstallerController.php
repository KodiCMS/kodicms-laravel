<?php namespace KodiCMS\Installer\Http\Controllers;

use Lang;
use Date;
use Assets;
use EnvironmentTester;
use KodiCMS\Installer\Installer;
use KodiCMS\Support\Helpers\Locale;
use KodiCMS\Installer\Exceptions\InstallValidationException;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;

class InstallerController extends FrontendController {
	/**
	 * @var Installer
	 */
	protected $installer;

	public function boot(Installer $installer)
	{
		$this->installer = $installer;
	}

	public function error()
	{
		$this->setTitle(trans('installer::core.title.not_installed'));
		$this->setContent('not_installed', [
			'title' => $this->template->title
		]);
	}

	public function run()
	{
		Assets::package(['steps', 'validate']);

		if ($locale = $this->request->get('lang') and array_key_exists($locale, Locale::getAvailable()))
		{
			$this->session->set('installer_locale', $locale);
		}
		else if ($locale = Locale::detectBrowser() and array_key_exists($locale, Locale::getAvailable()))
		{
			$this->session->set('installer_locale', $locale);
		}

		Lang::setLocale($this->session->get('installer_locale', Locale::getSystemDefault()));

		list($failed, $tests, $optional) = EnvironmentTester::check();

		$this->setContent('install', [
			'environment' => view("{$this->moduleNamespace}env", [
				'failed' => $failed,
				'tests' => $tests,
				'optional' => $optional
			]),
			'data' => $this->installer->getParameters(),
			'database' => $this->installer->getDatabaseParameters(),
			'locales' => config('cms.locales'),
			'selectedLocale' => Lang::locale(),
			'dateFormats' => config('cms.date_format_list'),
			'timezones' => Date::getTimezones(),
			'cacheTypes' => ['file' => 'File'],
			'sessionTypes' => ['file' => 'File', 'database' => 'Database']
		]);

		$this->templateScripts['FAILED'] = $failed;
	}

	public function install()
	{
		$this->autoRender = FALSE;
		$installData = $this->request->get('install', []);
		$databaseData = $this->request->get('database', []);

		try
		{
			$data = $this->installer->install($installData, $databaseData);

			return redirect(array_get($data, 'admin_dir_name'));
		}
		catch(InstallValidationException $e)
		{
			$this->throwValidationException(
				$this->request, $e->getValidator()
			);
		}
		catch(\Exception $e)
		{
			$this->throwFailException($this->smartRedirect()->withErrors($e->getMessage()));
		}
	}
}