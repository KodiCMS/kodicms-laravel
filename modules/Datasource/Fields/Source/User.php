<?php

namespace KodiCMS\Datasource\Fields\Source;

use KodiCMS\Datasource\Fields\Source;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Builder;
use KodiCMS\Users\Model\User as UserModel;
use KodiCMS\Datasource\Contracts\FieldInterface;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use KodiCMS\Widgets\Contracts\Widget as WidgetInterface;
use KodiCMS\Datasource\Contracts\FieldTypeRelationInterface;

class User extends Source implements FieldTypeRelationInterface
{
    /**
     * @var bool
     */
    protected $changeableDatabaseField = false;

    /**
     * @return array
     */
    public function booleanSettings()
    {
        return ['set_current', 'current_only', 'unique'];
    }

    /**
     * @return bool
     */
    public function isCurrentOnly()
    {
        return $this->getSetting('current_only');
    }

    /**
     * @return bool
     */
    public function isCurrentSet()
    {
        return $this->getSetting('set_current');
    }

    /**
     * @param Builder           $query
     * @param DocumentInterface $document
     */
    public function querySelectColumn(Builder $query, DocumentInterface $document)
    {
        parent::querySelectColumn($query, $document);
        $query->with($this->getRelationName());
    }

    /**
     * @param DocumentInterface $document
     *
     * @return array
     */
    public function getUserList(DocumentInterface $document)
    {
        $query = UserModel::query();

        if ($this->isUnique()) {
            $usedIds = \DB::table($this->getSection()->getSectionTableName())
                ->select($this->getDBKey())
                ->whereNotNull($this->getDBKey())
                ->where($this->getDBKey(), '!=', 0)
                ->where($this->getSection()->getDocumentPrimaryKey(), '!=', $document->getKey())
                ->lists($this->getDBKey());

            if (! empty($usedIds)) {
                $query->whereNotIn('id', $usedIds);
            }
        }

        $list = $query->get()->lists('username', 'id')->all();

        return [0 => 'Not set'] + $list;
    }

    /**
     * @param Blueprint $table
     *
     * @return \Illuminate\Support\Fluent
     */
    public function setDatabaseFieldType(Blueprint $table)
    {
        return $table->integer($this->getDBKey())->nullable();
    }

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetFormValue(DocumentInterface $document, $value)
    {
        if (($this->isCurrentSet() or $this->isCurrentOnly()) and ! $document->exists) {
            $value = auth()->user()->id;
        }

        return parent::onGetFormValue($document, $value);
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
        return ! is_null($user = $document->getAttribute($this->getRelationName())) ? $user->toArray() : $value;
    }

    /**
     * @param DocumentInterface     $document
     * @param SectionInterface|null $relatedSection
     * @param FieldInterface|null   $relatedField
     *
     * @return BelongsTo
     */
    public function getDocumentRelation(
        DocumentInterface $document, SectionInterface $relatedSection = null, FieldInterface $relatedField = null
    ) {
        $instance = (new UserModel)->newQuery();

        return new BelongsTo($instance, $document, $this->getDBKey(), 'id', $this->getRelationName());
    }

    /**
     * @return string
     */
    public function getRelationName()
    {
        return $this->getDBKey().'_users';
    }

    /**
     * @param DocumentInterface $document
     */
    public function onRelatedDocumentDeleting(DocumentInterface $document)
    {
    }

    /**************************************************************************
     * Events
     **************************************************************************/

    /**
     * @param DocumentInterface $document
     * @param mixed             $value
     *
     * @return mixed
     */
    public function onGetHeadlineValue(DocumentInterface $document, $value)
    {
        return ! is_null($user = $document->getAttribute($this->getRelationName()))
            ? link_to_route('backend.user.edit', $user->username, $user, ['class' => 'popup'])
            : null;
    }
}
