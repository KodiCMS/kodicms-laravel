<?php namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Datasource\DatasourceManager;

class SectionController extends Controller
{
	public function getIndex($dsId)
	{
		$sectionsTree = DatasourceManager::getSectionsTree();
	}

	public function getCreate($type)
	{

	}
}