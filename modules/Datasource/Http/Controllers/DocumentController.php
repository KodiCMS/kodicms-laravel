<?php namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Datasource\Repository\SectionRepository;

class DocumentController extends BackendController
{
	public function getCreate(SectionRepository $repository, $dsId)
	{
		$section = $repository->findOrFail($dsId);

		$document = $section->getEmptyDocument();

		dd($document->getEditableFields());
	}

	public function postCreate()
	{

	}

	public function getEdit()
	{

	}

	public function postEdit()
	{

	}
}