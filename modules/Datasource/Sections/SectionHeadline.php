<?php

namespace KodiCMS\Datasource\Sections;

use Illuminate\Http\JsonResponse;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class SectionHeadline implements SectionHeadlineInterface
{
    /**
     * @var SectionInterface
     */
    protected $section;

    /**
     * @var array
     */
    protected $fields = null;

    /**
     * @var int
     */
    protected $perPage = 20;

    /**
     * @var int
     */
    protected $currentPage;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var string
     */
    protected $template = 'datasource::section.headline';

    /**
     * @param SectionInterface $section
     */
    public function __construct(SectionInterface $section)
    {
        $this->section = $section;
    }

    /**
     * @return array
     */
    public function getHeadlineFields()
    {
        if (! is_null($this->fields)) {
            return $this->fields;
        }

        $this->fields = [];

        foreach ($this->section->getFields() as $field) {
            if (! $field->isVisible()) {
                continue;
            }

            $this->fields[$field->getKey()] = $field->getHeadlineParameters($this);

            if ($this->section->getDocumentTitleKey() == $field->getDBKey()) {
                $this->fields[$field->getKey()]['type'] = 'link';
            }
        }

        return $this->fields;
    }

    /**
     * @return array
     */
    public function getActiveFieldIds()
    {
        $fields = [];
        foreach ($this->section->getFields() as $field) {
            if (! $field->isVisible()) {
                continue;
            }

            $fields[] = $field->getDBKey();
        }

        return $fields;
    }

    /**
     * @return array
     */
    public function getSearchableFields()
    {
        $fields = array_filter($this->section->getFields(), function ($field) {
            return $field->isVisible() and $field->isSearchable();
        });

        return array_map(function ($field) {
            return $field->getName();
        }, $fields);
    }

    /**
     * @return array
     */
    public function getOrderingRules()
    {
        return $this->section->getHeadlineOrdering();
    }

    /**
     * @return array
     */
    public function getDocuments()
    {
        return $this->section->getEmptyDocument()
            ->getDocuments($this->getActiveFieldIds(), $this->getOrderingRules())
            ->paginate();
    }

    /**
     * @return JsonResponse
     */
    public function JsonResponse()
    {
        return new JsonResponse($this->render());
    }

    /**
     * @param string|null $template
     *
     * @return \Illuminate\View\View
     */
    public function render($template = null)
    {
        if (is_null($template)) {
            if (method_exists($this->section, 'getHeadlineTemplate')) {
                $template = $this->section->getHeadlineTemplate();
            } else {
                $template = $template = $this->template;
            }
        }

        return view($template, [
            'fieldParams' => $this->getHeadlineFields(),
            'items'       => $this->getDocuments(),
            'section'     => $this->section,
        ])->render();
    }

    /**
     * @return \Illuminate\View\View
     */
    public function renderOrderSettings()
    {
        return view('datasource::widgets.partials.ordering', [
            'ordering' => $this->getOrderingRules(),
            'fields'   => $this->section->getFields(),
        ])->render();
    }
}
