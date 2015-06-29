<?php namespace KodiCMS\Installer\Http\Controllers;

use Lang;
use Date;
use Assets;
use EnvironmentTester;
use KodiCMS\Installer\Installer;
use KodiCMS\Installer\Exceptions\InstallValidationException;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;

class InstallerController extends FrontendController {

	/**
	 * @var string
	 */
	public $moduleNamespace = 'installer::';

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

		list($failed, $tests, $optional) = EnvironmentTester::check();


		$this->setContent('install', [
			'environment' => view("{$this->moduleNamespace}env", [
				'failed' => $failed,
				'tests' => $tests,
				'optional' => $optional
			]),
			'title' => $this->template->title,
			'data' => $this->installer->getParameters(),
			'locales' => config('cms.locales'),
			'selectedLocale' => $this->request->get('locale', Lang::locale()),
			'dateFormats' => config('cms.date_format_list'),
			'timezones' => Date::getTimezones()
		]);

		$this->templateScripts['FAILED'] = $failed;
	}

	public function install()
	{
		$this->autoRender = FALSE;
		$data = $this->request->get('install', []);

		try
		{
			$data = $this->installer->install($data);
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