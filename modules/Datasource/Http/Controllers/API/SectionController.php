<?php

namespace KodiCMS\Datasource\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use KodiCMS\API\Http\Controllers\System\Controller;
use KodiCMS\Datasource\Repository\SectionRepository;

class SectionController extends Controller
{
    /**
     * @param SectionRepository $repository
     *
     * @return JsonResponse
     */
    public function getHeadline(SectionRepository $repository)
    {
        $sectionId = $this->getRequiredParameter('section_id');

        return $repository->findOrFail($sectionId)->getHeadline()->JsonResponse();
    }
}
