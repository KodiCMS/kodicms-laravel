<?php namespace KodiCMS\Installer\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Installer\Exceptions\InstallDatabaseException;
use KodiCMS\Installer\Installer;

class InstallerController extends Controller
{
	public function databaseCheck()
	{
		$post = (array) $this->getRequiredParameter('install');

		$post = array_only($post, ['db_host', 'db_username', 'db_password', 'db_database', 'db_prefix']);

		$this->setContent((new Installer)->checkDatabaseConnection($post));
	}
}