<?php

namespace KodiCMS\Datasource\Http\Controllers\API;

use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Datasource\Repository\SectionRepository;

class DocumentController extends Controller
{
    /**
     * @param SectionRepository $repository
     */
    public function deleteDelete(SectionRepository $repository)
    {
        $docIds = $this->getRequiredParameter('document');
        $sectionId = $this->getRequiredParameter('section_id');

        $repository->deleteDocuments($sectionId, $docIds);
    }

    /**
     * @param SectionRepository $repository
     */
    public function getFind(SectionRepository $repository)
    {
        $sectionId = $this->getRequiredParameter('section_id');
        $keyword = $this->getParameter('q');

        $documents = $repository->getDocumentsForRelationField($sectionId, $keyword);

        $this->setContent($documents);
    }
}
