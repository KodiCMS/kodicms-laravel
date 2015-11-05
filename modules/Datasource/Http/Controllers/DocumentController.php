<?php

namespace KodiCMS\Datasource\Http\Controllers;

use WYSIWYG;
use KodiCMS\Datasource\Repository\SectionRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DocumentController extends BackendController
{
    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getIndex()
    {
        return redirect()->route('backend.datasource.list', $this->request->cookie('currentDS'));
    }

    /**
     * @param SectionRepository $repository
     * @param int           $sectionId
     */
    public function getCreate(SectionRepository $repository, $sectionId)
    {
        WYSIWYG::loadAllEditors();

        $document = $repository->getEmptyDocument($sectionId);
        $section = $document->getSection();

        $this->breadcrumbs->add($section->getName(), route('backend.datasource.list', $section->getId()));

        $this->setTitle($section->getCreateDocumentTitle());

        $this->templateScripts['SECTION'] = $section;
        $this->templateScripts['DOCUMENT'] = $document;
        $document->onControllerLoad($this);

        $this->setContent($document->getCreateTemplate(), [
            'document' => $document,
            'section'  => $section,
            'fields'   => $document->getEditableFields(),
        ]);
    }

    /**
     * @param SectionRepository $repository
     * @param                   $sectionId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(SectionRepository $repository, $sectionId)
    {
        $data = $this->request->all();
        $repository->validateOnCreateDocument($sectionId, $data);

        $document = $repository->createDocument($sectionId, $data);

        return $this->smartRedirect([
            $sectionId,
            $document->getId(),
        ])
            ->with('success', trans($this->wrapNamespace('core.messages.document_updated'), [
                'title' => $document->getTitle(),
            ]));
    }

    /**
     * @param SectionRepository $repository
     * @param int           $sectionId
     * @param int|string    $documentId
     */
    public function getEdit(SectionRepository $repository, $sectionId, $documentId)
    {
        WYSIWYG::loadAllEditors();
        $document = $repository->getDocumentById($sectionId, $documentId);
        $section = $document->getSection();

        $document->onControllerLoad($this);
        $this->breadcrumbs->add($section->getName(), route('backend.datasource.list', $section->getId()));

        $this->setTitle($section->getEditDocumentTitle($document->getTitle()));

        $this->templateScripts['SECTION'] = $section;
        $this->templateScripts['DOCUMENT'] = $document;

        $this->setContent($document->getEditTemplate(), [
            'document' => $document,
            'section'  => $section,
            'fields'   => $document->getEditableFields(),
        ]);
    }

    /**
     * @param SectionRepository $repository
     * @param int               $sectionId
     * @param int               $documentId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(SectionRepository $repository, $sectionId, $documentId)
    {
        $document = $repository->getDocumentById($sectionId, $documentId);

        $data = $this->request->all();

        $repository->validateOnUpdateDocument($document, $data);

        $document = $repository->updateDocument($document, $data);

        return $this->smartRedirect([
            $sectionId,
            $document->getId(),
        ])
            ->with('success', trans($this->wrapNamespace('core.messages.document_updated'), [
                'title' => $document->getTitle(),
            ]));
    }
}
