<?php namespace KodiCMS\Datasource\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Datasource\Repository\SectionRepository;

class SectionController extends Controller
{
	/**
	 * @param SectionRepository $repository
	 */
	public function getHeadline(SectionRepository $repository)
	{
		$sectionId = $this->getRequiredParameter('section_id');

		$headline = $repository->findOrFail($sectionId)->getHeadline();

		$this->setContent($headline->response());
	}
}