<?php

namespace KodiCMS\CMS\Http\Controllers\API;

use Illuminate\Http\JsonResponse;
use KodiCMS\API\Exceptions\Exception;
use KodiCMS\CMS\Model\FileCollection;
use KodiCMS\API\Http\Controllers\System\Controller;

abstract class AbstractFileController extends Controller
{
    /**
     * @var FileCollection
     */
    protected $collection;

    /**
     * @var string
     */
    protected $sectionPrefix;

    /**
     * @return FileCollection
     */
    abstract protected function getCollection();

    /**
     * @return string
     */
    abstract protected function getSectionPrefix();

    /**
     * @param string $filename
     *
     * @return string
     */
    abstract protected function getRedirectToEditUrl($filename);

    public function before()
    {
        parent::before();
        $this->collection = $this->getCollection();
        $this->sectionPrefix = $this->getSectionPrefix();
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postCreate()
    {
        $data = $this->request->all();
        $file = $this->getFile();

        $file->fill(array_only($data, ['name', 'content', 'editor', 'roles']));

        $validator = $file->validator();

        if ($validator->fails()) {
            $this->throwValidationException($this->request, $validator);
        }

        $this->collection->saveFile($file)->saveSettings();

        $this->setMessage(
            trans($this->wrapNamespace("{$this->sectionPrefix}.messages.created"), [
                'name' => $file->getName(),
            ])
        );

        return redirect($this->getRedirectToEditUrl($file->getName()));
    }

    public function postEdit()
    {
        $filename = $this->getRequiredParameter('name');
        $data = $this->request->all();

        $file = $this->getFile($filename);
        $file->fill(array_only($data, ['name', 'content', 'editor', 'roles']));
        $validator = $file->validator();

        if ($validator->fails()) {
            $this->throwValidationException($this->request, $validator);
        }

        $this->collection->saveFile($file)->saveSettings();

        $this->setMessage(
            trans($this->wrapNamespace("{$this->sectionPrefix}.messages.updated"), [
                'name' => $file->getName(),
            ])
        );
    }

    /**
     * @param null|string $filename
     *
     * @return bool|\KodiCMS\CMS\Model\File
     */
    public function getFile($filename = null)
    {
        if (is_null($filename)) {
            return $this->collection->newFile();
        }

        if ($file = $this->collection->findFile($filename)) {
            return $file;
        }

        throw new Exception(
            trans($this->wrapNamespace("{$this->sectionPrefix}.messages.not_found"))
        );
    }

    /**
     * @return JsonResponse
     */
    public function getListForXEditable()
    {
        $collection = $this->collection->getHTMLSelectChoices();

        $data = array_map(function ($value, $key) {
            return ['id' => $key, 'text' => $value];
        }, $collection, array_keys($collection));

        return new JsonResponse($data);
    }
}
