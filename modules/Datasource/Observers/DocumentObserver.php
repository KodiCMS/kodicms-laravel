<?php namespace KodiCMS\Datasource\Observers;

use KodiCMS\Datasource\Model\Document;

class DocumentObserver {

	/**
	 * @param Document $document
	 */
	public function creating(Document $document)
	{
		foreach ($document->getSectionFields() as $key => $field)
		{
			$field->onDocumentCreating($document, $document->getAttribute($key));
		}
	}

	/**
	 * @param Document $document
	 */
	public function created(Document $document)
	{
		foreach ($document->getSectionFields() as $key => $field)
		{
			$field->onDocumentCreated($document, $document->getAttribute($key));
		}
	}

	/**
	 * @param Document $document
	 */
	public function updating(Document $document)
	{
		foreach ($document->getSectionFields() as $key => $field)
		{
			$field->onDocumentUpdating($document, $document->getAttribute($key));
		}
	}

	/**
	 * @param Document $document
	 */
	public function updated(Document $document)
	{

	}

	/**
	 * @param Document $document
	 */
	public function deleting(Document $document)
	{
		foreach ($document->getSectionFields() as $key => $field)
		{
			$field->onDocumentDeleting($document);
		}
	}

	/**
	 * @param Document $document
	 */
	public function deleted(Document $document)
	{

	}

}