<?php namespace KodiCMS\Datasource\Widget;

use Illuminate\Http\Request;
use KodiCMS\Widgets\Widget\Handler;
use KodiCMS\Datasource\Traits\WidgetDatasource;
use KodiCMS\Datasource\Repository\SectionRepository;
use KodiCMS\Datasource\Traits\WidgetDatasourceFields;

class DatasourceDocumentCreator extends Handler
{
	use WidgetDatasource, WidgetDatasourceFields;

	const GET = 1;
	const POST = 2;

	/**
	 * @var string
	 */
	protected $settingsTemplate = 'datasource::widgets.document_creator.settings';


	/**
	 * @return array
	 */
	public function prepareSettingsData()
	{
		$fields = !$this->getSection() ? [] : $this->section->getFields()->getEditable();
		return compact('fields');
	}

	/**
	 * @param Request $request
	 * @param SectionRepository $repository
	 */
	public function handle(Request $request, SectionRepository $repository)
	{
		if (!($section = $this->getSection()))
		{
			abort(404, 'Section not selected');
		}

		$sectionId = $section->getId();

		$data = $request->all();

		$repository->validateOnCreateDocument($sectionId, $data);
		$document = $repository->createDocument($sectionId, $data);
	}

}