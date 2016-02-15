<?php

namespace KodiCMS\CMS\Http\Controllers;

use WYSIWYG;
use KodiCMS\Users\Model\UserRole;
use KodiCMS\CMS\Model\FileCollection;

abstract class AbstractFileController extends System\BackendController
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

    public function before()
    {
        parent::before();
        $this->collection = $this->getCollection();
        $this->sectionPrefix = $this->getSectionPrefix();
    }

    public function getIndex()
    {
        $this->setContent("{$this->sectionPrefix}.list", [
            'collection' => $this->collection,
        ]);
    }

    public function getCreate()
    {
        $file = $this->getFile();
        $roles = UserRole::lists('name', 'name')->all();

        $this->setTitle(trans($this->wrapNamespace("{$this->sectionPrefix}.title.create")));
        $this->templateScripts['FILE'] = $file->toArray();

        $this->setContent("{$this->sectionPrefix}.create", compact('file', 'roles'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
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

        return $this->smartRedirect(['name' => $file->getName()])
            ->with('success', trans($this->wrapNamespace("{$this->sectionPrefix}.messages.created"), [
                'name' => $file->getName(),
            ]));
    }

    /**
     * @param string $filename
     */
    public function getEdit($filename)
    {
        $file = $this->getFile($filename);
        $roles = UserRole::lists('name', 'name')->all();

        $this->setTitle(trans($this->wrapNamespace("{$this->sectionPrefix}.title.edit"), [
            'name' => $file->getName(),
        ]));

        $this->templateScripts['FILE'] = $file->toArray();

        $this->setContent("{$this->sectionPrefix}.edit", compact('file', 'roles'));
    }

    /**
     * @param string $filename
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit($filename)
    {
        $data = $this->request->all();

        $file = $this->getFile($filename);
        $file->fill(array_only($data, ['name', 'content', 'editor', 'roles']));
        $validator = $file->validator();

        if ($validator->fails()) {
            $this->throwValidationException($this->request, $validator);
        }

        $this->collection->saveFile($file)->saveSettings();

        return $this->smartRedirect(['name' => $file->getName()])
            ->with('success', trans($this->wrapNamespace("{$this->sectionPrefix}.messages.updated"), [
                'name' => $file->getName(),
            ]));
    }

    /**
     * @param string $filename
     *
     * @return $this|\Illuminate\Http\RedirectResponse
     */
    public function postDelete($filename)
    {
        $this->autoRender = false;

        $file = $this->getFile($filename);

        if ($file->delete()) {
            return $this->smartRedirect()
                ->with('success', trans($this->wrapNamespace("{$this->sectionPrefix}.messages.deleted"), [
                    'name' => $file->getName(),
                ]));
        }

        return $this->smartRedirect()
            ->withErrors(
                trans($this->wrapNamespace("{$this->sectionPrefix}.messages.not_deleted"))
            );
    }

    /**
     * @param null|string $filename
     *
     * @return mixed
     */
    public function getFile($filename = null)
    {
        WYSIWYG::loadAllEditors();

        if (is_null($filename)) {
            return $this->collection->newFile();
        }

        if ($file = $this->collection->findFile($filename)) {
            return $file;
        }

        $this->throwFailException(
            $this->smartRedirect()->withErrors(trans($this->wrapNamespace("{$this->sectionPrefix}.messages.not_found")))
        );
    }
}
