<?php

namespace Plugins\butschster\DSTags\Fields;

use Assets;
use DatasourceManager;
use KodiCMS\Support\Helpers\UI;
use Illuminate\Database\Schema\Blueprint;
use KodiCMS\Datasource\Fields\Relation\ManyToMany;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\CMS\Http\Controllers\System\TemplateController;

class Tags extends ManyToMany
{
    /**
     * @var bool
     */
    protected $hasDatabaseColumn = true;

    /**
     * @var array
     */
    protected $currentDocuments = [];

    /**
     * @var array
     */
    protected $newTags = [];

    /**
     * @return array
     */
    public function getSectionList()
    {
        return DatasourceManager::getSectionsFormHTML(['tags']);
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->text($this->getDBKey())->default('');
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return ! empty($value) ? implode(' ', array_map(function ($tag) {
            return UI::label($tag);
        }, $this->makeTagArray($value))) : null;
    }

    /**
     * @param DocumentInterface $document
     * @param WidgetInterface   $widget
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetWidgetValue(DocumentInterface $document, WidgetInterface $widget, $value)
    {
        return $this->makeTagArray($value);
    }

    /**
     * @param DocumentInterface  $document
     * @param TemplateController $controller
     */
    public function onControllerLoad(DocumentInterface $document, TemplateController $controller)
    {
        Assets::loadPackage(['jquery-tagsinput', 'jquery-ui']);
        parent::onControllerLoad($document, $controller);
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentFill(DocumentInterface $document, $value)
    {
        $this->selectedDocuments = $this->makeTagArray($value);
        $this->currentDocuments = $this->getRelatedDocumentValues($document);
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     */
    public function onDocumentCreated(DocumentInterface $document, $value)
    {
        $this->selectedDocuments = $this->tag($this->selectedDocuments);
        parent::onDocumentCreated($document, $value);
    }

    /**
     * @param DocumentInterface $document
     * @param                   $value
     */
    public function onDocumentUpdating(DocumentInterface $document, $value)
    {
        $oldTags = array_diff($this->currentDocuments, $this->selectedDocuments);
        $newTags = array_diff($this->selectedDocuments, $this->currentDocuments);

        $newTags = $this->tag($newTags);
        $oldTags = $this->untag($oldTags);

        if (! empty($oldTags)) {
            $document->{$this->getRelationName()}()->detach($oldTags);
        }

        if (! empty($newTags)) {
            $document->{$this->getRelationName()}()->attach($newTags);
        }
    }

    /**
     * @param array $tagNames
     *
     * @return array
     */
    public function tag(array $tagNames)
    {
        $tagNames = $this->makeTagArray($tagNames);

        $ids = [];

        foreach ($tagNames as $tagName) {
            $ids[] = $this->addTag($tagName);
        }

        return $ids;
    }

    /**
     * @param array $tagNames
     *
     * @return array
     */
    public function untag(array $tagNames)
    {
        $ids = [];

        foreach ($tagNames as $tagName) {
            $ids[] = $this->removeTag($tagName);
        }

        return $ids;
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    protected function fetchDocumentTemplateValues(DocumentInterface $document)
    {
        return [
            'value'    => $document->getFormValue($this->getDBKey()),
            'document' => $document,
            'section'  => $document->getSection(),
        ];
    }

    /**
     * @param string $tagName
     *
     * @return int
     */
    private function addTag($tagName)
    {
        $relatedSection = $this->relatedSection;

        $tagName = trim($tagName);
        $tag = $relatedSection->getEmptyDocument()->where('name', $tagName)->first();

        if (is_null($tag)) {
            $tag = $relatedSection->getEmptyDocument();
            $tag->name = $tagName;

            $tag->save();
        }

        $tag->increment('count');

        return $tag->getId();
    }

    /**
     * @param string $tagName
     *
     * @return int
     */
    private function removeTag($tagName)
    {
        $relatedSection = $this->relatedSection;

        $tagName = trim($tagName);

        $tag = $relatedSection->getEmptyDocument()->where('name', $tagName)->first();

        if (is_null($tag)) {
            $tag = $relatedSection->getEmptyDocument();
            $tag->name = $tagName;

            $tag->save();
        }

        if ($tag->count > 0) {
            $tag->decrement('count');
        }

        return $tag->getId();
    }

    /**
     * @param string|array $tagNames
     *
     * @return array
     */
    private function makeTagArray($tagNames)
    {
        if (is_string($tagNames)) {
            $tagNames = explode(',', $tagNames);
        } elseif (! is_array($tagNames)) {
            $tagNames = [null];
        }

        $tagNames = array_map('trim', $tagNames);

        return array_unique(array_filter($tagNames));
    }
}
