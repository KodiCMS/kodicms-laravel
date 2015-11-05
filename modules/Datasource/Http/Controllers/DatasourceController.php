<?php

namespace KodiCMS\Datasource\Http\Controllers;

use DatasourceManager;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Datasource\Repository\SectionRepository;

class DatasourceController extends BackendController
{
    const DS_COOKIE_NAME = 'currentDS';

    /**
     * @var string
     */
    public $moduleNamespace = 'datasource::';

    /**
     * @param SectionRepository $repository
     * @param int           $sectionId
     */
    public function getIndex(SectionRepository $repository, $sectionId = null)
    {
        if (is_null($sectionId)) {
            $sectionId = $this->request->cookie(static::DS_COOKIE_NAME);
        }

        if (is_null($sectionId)) {
            $section = $repository->query()->first();
        } else {
            $section = $repository->findOrFail($sectionId);
        }

        if (! is_null($section)) {
            $headline = $section->getHeadline()->render();
            $toolbar = $section->getToolbar()->render();
            $this->setTitle($section->getName());

            $this->response->withCookie(cookie()->forever(static::DS_COOKIE_NAME, $section->getId()));
        } else {
            $section = $headline = $toolbar = null;
        }

        $this->setContent('content', [
            'navigation' => view('datasource::navigation', [
                'types'    => DatasourceManager::getAvailableTypes(),
                'sections' => DatasourceManager::getSections(),
            ]),
            'section'    => view('datasource::section', [
                'headline' => $headline,
                'toolbar'  => $toolbar,
                'section'  => $section,
            ]),
        ]);

        view()->share('currentSection', $section);

        $this->templateScripts['SECTION'] = $section;
    }

    /**
     * @param SectionRepository $repository
     * @param string            $type
     *
     * @throws SectionException
     */
    public function getCreate(SectionRepository $repository, $type)
    {
        $type = strtolower($type);

        $section = $repository->instanceByType($type);
        $typeObject = $section->getType();

        $this->setTitle(trans($this->wrapNamespace('core.title.create'), ['type' => $typeObject->getTitle()]));
        $this->setContent($typeObject->getCreateTemplate(), compact('typeObject', 'section'));
    }

    /**
     * @param SectionRepository $repository
     * @param string            $type
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \KodiCMS\Datasource\Exceptions\SectionException
     */
    public function postCreate(SectionRepository $repository, $type)
    {
        $type = strtolower($type);

        $data = $this->request->except(['type']);
        $data['type'] = $type;

        $repository->validateOnCreate($data);

        $section = $repository->create($data);

        return $this->smartRedirect([$section->getId()])
            ->with('success', trans($this->wrapNamespace('core.messages.section.created'), [
                'title' => $section->name,
            ]));
    }

    /**
     * @param SectionRepository $repository
     * @param int           $sectionId
     *
     * @throws SectionException
     */
    public function getEdit(SectionRepository $repository, $sectionId)
    {
        $section = $repository->findOrFail($sectionId);

        $this->breadcrumbs->add($section->getName(), route('backend.datasource.list', $section->getId()));

        $this->setTitle(trans($this->wrapNamespace('core.title.edit'), ['name' => $section->getName()]));

        $this->setContent($section->getType()->getEditTemplate(), [
            'section' => $section,
            'fields'  => $section->getFields(),
        ]);
    }

    /**
     * @param SectionRepository $repository
     * @param                   $sectionId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(SectionRepository $repository, $sectionId)
    {
        $data = $this->request->except(['type']);
        $repository->validateOnUpdate($data);
        $section = $repository->update($sectionId, $data);

        return $this->smartRedirect([$section->getId()])
            ->with('success', trans($this->wrapNamespace('core.messages.section.updated'), [
                'title' => $section->getName(),
            ]));
    }

    /**
     * @param SectionRepository $repository
     * @param int               $sectionId
     *
     * @return $this
     */
    public function getRemove(SectionRepository $repository, $sectionId)
    {
        $repository->delete($sectionId);

        return redirect()->route('backend.datasource.list')
            ->withCookie(
                cookie()->forget(static::DS_COOKIE_NAME)
            );
    }
}
