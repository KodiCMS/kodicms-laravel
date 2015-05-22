<?php namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\Controller;
use KodiCMS\Datasource\DatasourceManager;
use KodiCMS\Datasource\Model\Section;

class DatasourceController extends Controller
{
	public function getIndex($dsId)
	{
		/*$sectionsTree = DatasourceManager::getSectionsTree();*/

		$section = Section::find($dsId)->toSection();


		dd($section);
	}

	public function getCreate($type)
	{

	}
}