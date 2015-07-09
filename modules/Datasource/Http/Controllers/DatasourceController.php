<?php namespace KodiCMS\Datasource\Http\Controllers;

use DatasourceManager;
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
		$sectionModel = Section::findOrFail($dsId);
		$section = $sectionModel->toSection();

		$this->setTitle($sectionModel->name);

		$this->setContent('content', [
			'navigation' => view('datasource::navigation', [
				'types' => DatasourceManager::getAvailableSectionTypes(),
				'sections' => DatasourceManager::getSections()
			]),
			'section' => view('datasource::section', [
				'headline' => $section->getHeadline(),
				'toolbar' => $section->getToolbar(),
				'section' => $section
			])
		]);

		view()->share('dsModel', $sectionModel);

		$this->templateScripts['DS'] = $sectionModel;
	}

	public function getCreate($type)
	{

	}
}