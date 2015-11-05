<?php

namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\Datasource\Repository\FieldRepository;
use KodiCMS\Datasource\Repository\SectionRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class FieldController extends BackendController
{
    /**
     * @param SectionRepository $sectionRepository
     * @param FieldRepository   $repository
     * @param int           $dsId
     */
    public function getCreate(SectionRepository $sectionRepository, FieldRepository $repository, $dsId)
    {
        $section = $sectionRepository->findOrFail($dsId);

        $this->breadcrumbs
            ->add($section->getName(), route('backend.datasource.list', $section->getId()))
            ->add('Edit section', route('backend.datasource.edit', $section->getId()));

        $this->setTitle('Create field');

        $this->templateScripts['SECTION_ID'] = $dsId;

        $this->setContent('field.create', [
            'field'    => $repository->instance(),
            'section'  => $section,
            'sections' => $repository->getSectionsForSelect(),
        ]);
    }

    /**
     * @param FieldRepository $repository
     * @param int         $dsId
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \KodiCMS\Datasource\Exceptions\FieldException
     */
    public function postCreate(FieldRepository $repository, $dsId)
    {
        $data = $this->request->except(['section_id', 'is_system']);
        $data['section_id'] = $dsId;
        $repository->validateOnCreate($data);
        $field = $repository->create($data);

        return redirect()
            ->route('backend.datasource.field.edit', $field->id)
            ->with('success', trans($this->wrapNamespace('core.messages.field.created'), [
                'title' => $field->name,
            ]));
    }

    /**
     * @param FieldRepository $repository
     * @param int         $fieldId
     */
    public function getEdit(FieldRepository $repository, $fieldId)
    {
        $field = $repository->findOrFail($fieldId);
        $section = $field->section;

        $this->breadcrumbs
            ->add($section->getName(), route('backend.datasource.list', $section->getId()))
            ->add('Edit section', route('backend.datasource.edit', $section->getId()));

        $this->setTitle("Edit field [{$field->getTypeTitle()}::{$field->getName()}]");

        $this->templateScripts['SECTION_ID'] = $section->getId();
        $this->templateScripts['FIELD_ID'] = $field->getId();

        $this->setContent('field.edit', [
            'field'    => $field,
            'section'  => $section,
            'sections' => $repository->getSectionsForSelect(),
        ]);
    }

    /**
     * @param FieldRepository $repository
     * @param int         $fieldId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(FieldRepository $repository, $fieldId)
    {
        $data = $this->request->except(['key', 'section_id', 'is_system', 'related_section_id', 'type']);

        $repository->validateOnUpdate($data);
        $field = $repository->update($fieldId, $data);

        return $this->smartRedirect([$field->getId()])
            ->with('success', trans($this->wrapNamespace('core.messages.field.updated'), [
                'title' => $field->getName(),
            ]));
    }

    public function getLocation()
    {
    }
}
