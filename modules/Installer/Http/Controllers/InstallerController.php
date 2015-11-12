<?php

namespace KodiCMS\Installer\Http\Controllers;

use Lang;
use Date;
use Meta;
use EnvironmentTester;
use KodiCMS\Installer\Installer;
use KodiCMS\Support\Helpers\Locale;
use KodiCMS\Installer\Exceptions\InstallValidationException;
use KodiCMS\CMS\Http\Controllers\System\FrontendController;

class InstallerController extends FrontendController
{
    /**
     * @var Installer
     */
    protected $installer;

    /**
     * @param Installer $installer
     */
    public function boot(Installer $installer)
    {
        $this->installer = $installer;
    }

    public function error()
    {
        $this->setTitle(trans('installer::core.title.not_installed'));
        $this->setContent('not_installed', [
            'title' => $this->template->title,
        ]);
    }

    public function run()
    {
        Meta::loadPackage('steps', 'validate');

        if ($locale = $this->request->get('lang') and array_key_exists($locale, Locale::getAvailable())) {
            $this->session->set('installer_locale', $locale);
        } elseif ($locale = Locale::detectBrowser() and array_key_exists($locale, Locale::getAvailable())) {
            $this->session->set('installer_locale', $locale);
        }

        Lang::setLocale($this->session->get('installer_locale', Locale::getSystemDefault()));

        list($failed, $tests, $optional) = EnvironmentTester::check();

        $moduleNamespace = \ModulesFileSystem::getModuleNameByNamespace().'::';
        $this->setContent('install', [
            'environment'    => view("{$moduleNamespace}env", [
                'failed'   => $failed,
                'tests'    => $tests,
                'optional' => $optional,
            ]),
            'data'           => $this->installer->getParameters(),
            'dbDrivers'      => $this->installer->getAvailableDatabaseDrivers(),
            'database'       => $this->installer->getDatabaseParameters(),
            'locales'        => config('cms.locales'),
            'selectedLocale' => Lang::locale(),
            'dateFormats'    => config('cms.date_format_list'),
            'timezones'      => Date::getTimezones(),
            'cacheDrivers'   => $this->installer->getAvailableCacheTypes(),
            'sessionDrivers' => $this->installer->getAvailableSessionTypes(),
        ]);

        $this->templateScripts['FAILED'] = $failed;
    }

    public function install()
    {
        $this->autoRender = false;
        $installData = $this->request->get('install', []);
        $databaseData = $this->request->get('database', []);

        try {
            $data = $this->installer->install($installData, $databaseData);

            return redirect(array_get($data, 'admin_dir_name'));
        } catch (InstallValidationException $e) {
            $this->throwValidationException($this->request, $e->getValidator());
        } catch (\Exception $e) {
            $this->throwFailException($this->smartRedirect()->withErrors($e->getMessage()));
        }
    }
}
