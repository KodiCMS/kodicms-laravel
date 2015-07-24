<?php namespace KodiCMS\Datasource\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Datasource\Repository\SectionRepository;

class DocumentController extends Controller
{
	public function deleteDelete(SectionRepository $repository)
	{
		$docIds = $this->getRequiredParameter('document');
		$sectionId = $this->getRequiredParameter('section_id');

		$repository->deleteDocuments($sectionId, $docIds);
	}
}