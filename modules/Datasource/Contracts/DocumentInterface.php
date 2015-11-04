<?php

namespace KodiCMS\Datasource\Contracts;

use Illuminate\Validation\Validator;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;

interface DocumentInterface
{
    /**
     * @return string|int
     */
    public function getId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getEditLink();

    /**
     * @return string
     */
    public function getCreateLink();

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasField($key);

    /**
     * @param  string $key
     *
     * @return mixed
     */
    public function getFormValue($key);

    /**
     * Get a plain attribute (not a relationship).
     *
     * @param  string          $key
     * @param  WidgetInterface $widget
     *
     * @return mixed
     */
    public function getWidgetValue($key, WidgetInterface $widget);

    /**
     * @param  string $key
     *
     * @return mixed
     */
    public function getHeadlineValue($key);

    /**
     * @param SectionHeadlineInterface $headline
     *
     * @return array
     */
    public function toHeadlineArray(SectionHeadlineInterface $headline);

    /**
     * @return SectionInterface
     */
    public function getSection();

    /**
     * @return array
     */
    public function getFieldsNames();

    /**
     * @return array
     */
    public function getSectionFields();

    /**
     * @return array
     */
    public function getEditableFields();

    /**
     * @return string
     */
    public function getEditTemplate();

    /**
     * @return string
     */
    public function getCreateTemplate();

    /**
     * @return string
     */
    public function getFormTemplate();

    /**
     * @param Validator $validator
     *
     * @return array
     */
    public function getValidationRules(Validator $validator);

    /**
     * @param int|string      $id
     * @param array|null          $fields
     * @param string|int|null $primaryKeyField
     *
     * @return DocumentInterface|null
     */
    public function getDocumentById($id, array $fields = null, $primaryKeyField = null);

    /**
     * @param bool|array|null $fields
     * @param array           $orderRules
     * @param array           $filterRules
     *
     * @return Collection
     */
    public function getDocuments($fields = true, array $orderRules = [], array $filterRules = []);

    /**
     * @param TemplateController $controller
     */
    public function onControllerLoad(TemplateController $controller);
}
