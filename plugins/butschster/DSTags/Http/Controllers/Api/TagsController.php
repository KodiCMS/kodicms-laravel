<?php namespace Plugins\butschster\DSTags\Http\Controllers\Api;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Datasource\Repository\SectionRepository;

class TagsController extends Controller
{
	/**
	 * @param SectionRepository $repository
	 * @param $sectionId
	 */
	public function getTags(SectionRepository $repository)
	{
		$sectionId = $this->getRequiredParameter('section_id');
		$keyword = $this->getParameter('tag');

		$documents = $repository->getDocumentsForRelationField($sectionId, $keyword);
		$this->setContent($documents);
	}
}