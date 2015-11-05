<?php

namespace KodiCMS\Datasource\Repository;

use DatasourceManager;
use KodiCMS\Datasource\Model\Section;
use KodiCMS\CMS\Repository\BaseRepository;
use KodiCMS\Datasource\Contracts\DocumentInterface;
use KodiCMS\Datasource\Exceptions\SectionException;

class SectionRepository extends BaseRepository
{
    /**
     * @param Section $model
     */
    public function __construct(Section $model)
    {
        parent::__construct($model);
    }

    /**
     * @param string $type
     * @param array  $attributes
     *
     * @return Section
     * @throws SectionException
     */
    public function instanceByType($type, array $attributes = [])
    {
        $attributes['type'] = $type;

        return $this->model->newInstance($attributes);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdate(array $data = [])
    {
        $validator = $this->validator($data, [
            'name' => 'required',
        ]);

        return $this->_validate($validator);
    }

    /**
     * @param int $sectionId
     * @param int $folderId
     *
     * @return bool
     */
    public function moveToFolder($sectionId, $folderId)
    {
        $this->findOrFail($sectionId)->update([
            'folder_id' => (int) $folderId,
        ]);

        return true;
    }

    /**
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     * @throws SectionException
     */
    public function create(array $data = [])
    {
        if (is_null($type = array_get($data, 'type'))) {
            throw new SectionException('Type not set');
        }

        if (is_null($typeObject = DatasourceManager::getTypeObject($type))) {
            throw new SectionException("Datasource type {$type} not found");
        }

        $section = parent::create($data);
        DatasourceManager::createTableSection($section);

        return $section;
    }

    /**
     * @param int $id
     *
     * @return Model
     * @throws \Exception
     */
    public function delete($id)
    {
        $model = $this->findOrFail($id);
        $model->delete();
        $model->fields()->delete();

        DatasourceManager::dropSectionTable($model);

        return $model;
    }

    /**
     * @param int     $sectionId
     * @param string|null $keyword
     *
     * @return array|static[]
     */
    public function getDocumentsForRelationField($sectionId, $keyword = null)
    {
        $section = $this->findOrFail($sectionId);

        return \DB::table($section->getSectionTableName())
            ->select('*')
            ->selectRaw("{$section->getDocumentPrimaryKey()} as id")
            ->selectRaw("{$section->getDocumentTitleKey()} as text")
            ->where($section->getDocumentTitleKey(), 'like', '%'.$keyword.'%')
            ->get();
    }

    /**
     * @param int $sectionId
     *
     * @return DocumentInterface
     */
    public function getEmptyDocument($sectionId)
    {
        return $this->findOrFail($sectionId)->getEmptyDocument();
    }

    /**
     * @param int $sectionId
     * @param int $documentId
     *
     * @return DocumentInterface
     */
    public function getDocumentById($sectionId, $documentId)
    {
        return $this->findOrFail($sectionId)->getDocumentById($documentId);
    }

    /**
     * @param int $sectionId
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnCreateDocument($sectionId, array $data = [])
    {
        $document = $this->findOrFail($sectionId)->getEmptyDocument();

        $data = array_only($data, $document->getEditableFields()->getKeys());

        $validator = $this->validator($data);
        $validator->setRules($document->getValidationRules($validator));
        $validator->setAttributeNames($document->getFieldsNames());

        return $this->_validate($validator);
    }

    /**
     * @param array $data
     *
     * @return bool
     * @throws \KodiCMS\CMS\Exceptions\ValidationException
     */
    public function validateOnUpdateDocument(DocumentInterface $document, array $data = [])
    {
        $data = array_only($data, $document->getEditableFields()->getKeys());

        $validator = $this->validator($data);
        $validator->setRules($document->getValidationRules($validator));

        return $this->_validate($validator);
    }

    /**
     * @param int $sectionId
     * @param array   $data
     *
     * @return mixed
     */
    public function createDocument($sectionId, array $data)
    {
        $document = $this->findOrFail($sectionId)->getEmptyDocument();
        $data = array_only($data, $document->getEditableFields()->getKeys());

        $document->fill($data)->save();

        return $document;
    }

    /**
     * @param DocumentInterface $document
     * @param array             $data
     *
     * @return mixed
     */
    public function updateDocument(DocumentInterface $document, array $data)
    {
        $data = array_only($data, $document->getEditableFields()->getKeys());
        $document->update($data);

        return $document;
    }

    /**
     * @param int $sectionId
     * @param array   $ids
     */
    public function deleteDocuments($sectionId, array $ids)
    {
        $section = $this->findOrFail($sectionId);

        $documents = $section->getEmptyDocument()->whereIn($section->getDocumentPrimaryKey(), $ids);

        foreach ($documents->get() as $document) {
            $document->delete();
        }
    }
}
