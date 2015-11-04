<?php

namespace KodiCMS\Datasource\Fields\Source;

use Request;
use DatasourceManager;
use KodiCMS\Datasource\Fields\Relation;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Images extends Relation\ManyToMany
{
    /**
     * @var array
     */
    protected $deletingDocuments = [];

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    public function getRelatedDocumentValues(DocumentInterface $document)
    {
        if (! is_null($relatedField = $this->relatedField)) {
            $section = $relatedField->getSection();

            return $this->getDocumentRelation($document, $section, $relatedField)->get()->all();
        }

        return [];
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
        $documentIds = [];

        $section = $this->relatedSection;

        Model::unguard();
        foreach ($value as $file) {
            if (is_null($file)) {
                continue;
            }

            if ($file instanceof UploadedFile) {
                $imageDocument = $section->getEmptyDocument();

                $imageDocument->fill([
                    'header' => $document->getTitle(),
                    'image'  => $file,
                ])->save();
                $documentIds[] = $imageDocument->getId();
            }
        }
        Model::reguard();

        $this->deletingDocuments = (array) Request::get($this->getDBKey().'_remove');
        $this->selectedDocuments = array_unique(array_merge($documentIds, (array) Request::get($this->getDBKey().'_selected')));
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentCreated(DocumentInterface $document, $value)
    {
        $document->{$this->getRelationName()}()->attach((array) $this->selectedDocuments);
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentUpdating(DocumentInterface $document, $value)
    {
        $relation = $document->{$this->getRelationName()}();
        $relation->attach((array) $this->selectedDocuments);

        if (! empty($this->deletingDocuments)) {
            $relation->detach((array) $this->deletingDocuments);
        }
    }

    /**
     * @return array
     */
    public function getSectionList()
    {
        return DatasourceManager::getSectionsFormHTML(['images']);
    }

    /**
     * @return array
     */
    public function getAllowedTypes()
    {
        return $this->relatedSection->getFields()->getByKey('image')->getAllowedTypes();
    }

    /**
     * @return int
     */
    public function getMaxFileSize()
    {
        return $this->relatedSection->getFields()->getByKey('image')->getMaxFileSize();
    }
}
