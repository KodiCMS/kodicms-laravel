<?php namespace KodiCMS\Datasource\Http\Controllers;

use KodiCMS\Datasource\Repository\SectionRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DocumentController extends BackendController
{
	/**
	 * @param SectionRepository $repository
	 * @param integer $sectionId
	 */
	public function getCreate(SectionRepository $repository, $sectionId)
	{
		$document = $repository->getEmptyDocument($sectionId);
		$section = $document->getSection();

		$this->breadcrumbs
			->add($section->getName(), route('backend.datasource.list', $section->getId()));

		$this->setTitle(trans($this->wrapNamespace('document.title.create')));

		$this->setContent('document.create', [
			'document' => $document,
			'section' => $section,
			'fields' => $document->getEditableFields()
		]);
	}

	public function postCreate(SectionRepository $repository, $sectionId)
	{
		$data = $this->request->all();
		$repository->validateOnCreateDocument($sectionId, $data);

		$document = $repository->createDocument($sectionId, $data);

		return $this->smartRedirect([$sectionId, $document->getId()])
			->with('success', trans($this->wrapNamespace('document.messages.updated'), ['title' => $document->getTitle()]));
	}

	/**
	 * @param SectionRepository $repository
	 * @param integer $sectionId
	 * @param integer|string $documentId
	 */
	public function getEdit(SectionRepository $repository, $sectionId, $documentId)
	{
		$document = $repository->getDocumentById($sectionId, $documentId);
		$section = $document->getSection();

		$this->breadcrumbs
			->add($section->getName(), route('backend.datasource.list', $section->getId()));

		$this->setTitle(trans($this->wrapNamespace('document.title.edit'), ['header' => $document->getTitle()]));

		$this->setContent('document.edit', [
			'document' => $document,
			'section' => $section,
			'fields' => $document->getEditableFields()
		]);
	}

	public function postEdit(SectionRepository $repository, $sectionId, $documentId)
	{
		$document = $repository->getDocumentById($sectionId, $documentId);

		$data = $this->request->all();
		$repository->validateOnUpdateDocument($document, $data);

		$document = $repository->updateDocument($document, $data);

		return $this->smartRedirect([$document->getId()])
			->with('success', trans($this->wrapNamespace('document.messages.updated'), ['title' => $document->getTitle()]));
	}
}