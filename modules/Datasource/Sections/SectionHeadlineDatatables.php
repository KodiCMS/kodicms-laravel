<?php

namespace KodiCMS\Datasource\Sections;

use Meta;
use Illuminate\Http\JsonResponse;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\SectionHeadlineInterface;

class SectionHeadlineDatatables implements SectionHeadlineInterface
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
     * @var string
     */
    protected $template = 'datasource::section.headline_datatables';

    /**
     * @param SectionInterface $section
     */
    public function __construct(SectionInterface $section)
    {
        $this->section = $section;
        Meta::loadPackage('datatables');
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
    }

    /**
     * @return array
     */
    public function getSearchableFields()
    {
    }

    /**
     * @return array
     */
    public function getOrderingRules()
    {
    }

    /**
     * @return array
     */
    public function getDocuments()
    {
        $document = $this->section->getEmptyDocument();

        return app('datatables')->usingDatasourceEngine($document, $this)->make();
    }

    /**
     * @return JsonResponse
     */
    public function JsonResponse()
    {
        return $this->getDocuments();
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
                $template = $this->template;
            }
        }

        return view($template, [
            'fieldParams' => $this->getHeadlineFields(),
            'section'     => $this->section,
        ]);
    }

    /**
     * @return \Illuminate\View\View|null
     */
    public function renderOrderSettings()
    {
        return;
    }
}
