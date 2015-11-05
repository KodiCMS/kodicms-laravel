<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Http\JsonResponse;

interface SectionHeadlineInterface
{
    /**
     * @return array
     */
    public function getHeadlineFields();

    /**
     * @return array
     */
    public function getActiveFieldIds();

    /**
     * @return array
     */
    public function getSearchableFields();

    /**
     * @return array
     */
    public function getDocuments();

    /**
     * @return JsonResponse
     */
    public function JsonResponse();

    /**
     * @param string|null $template
     *
     * @return \Illuminate\View\View
     */
    public function render($template = null);

    /**
     * @return \Illuminate\View\View|null
     */
    public function renderOrderSettings();
}
