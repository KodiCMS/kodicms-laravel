<?php namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\Datasource\Model\Section;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DatasourceController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'datasource::';

	public function getIndex($dsId)
	{
		/*$sectionsTree = DatasourceManager::getSectionsTree();*/

		$sectionModel = Section::findOrFail($dsId);
		$section = $sectionModel->toSection();

		$this->setTitle($sectionModel->name);

		$this->setContent('sections', [
			'navigation' => view('datasource::navigation'),
			'headline' => $section->getHeadline(),
			'toolbar' => $section->getToolbar()
		]);

		view()->share('dsModel', $sectionModel);
		view()->share('ds', $section);

		$this->templateScripts['DS'] = $sectionModel;
	}

	public function getCreate($type)
	{

	}
}