<?php namespace KodiCMS\Datasource\Http\Controllers;

use DatasourceManager;
use KodiCMS\CMS\Http\Controllers\System\BackendController;
use KodiCMS\Datasource\Repository\SectionRepository;

class DatasourceController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'datasource::';

	/**
	 * @param SectionRepository $repository
	 * @param integer $sectionId
	 */
	public function getIndex(SectionRepository $repository, $sectionId = null)
	{
		if (!is_null($sectionId))
		{
			$section = $repository->findOrFail($sectionId);
			$headline = $section->getHeadline()->render();
			$toolbar = $section->getToolbar()->render();
			$this->setTitle($section->getName());
		}
		else
		{
			$section = $headline = $toolbar = null;
		}

		$this->setContent('content', [
			'navigation' => view('datasource::navigation', [
				'types' => DatasourceManager::getAvailableTypes(),
				'sections' => DatasourceManager::getSections()
			]),
			'section' => view('datasource::section', [
				'headline' => $headline,
				'toolbar' => $toolbar,
				'section' => $section
			])
		]);

		view()->share('currentSection', $section);

		$this->templateScripts['SECTION'] = $section;
	}

	/**
	 * @param SectionRepository $repository
	 * @param string $type
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
	 * @param string $type
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
			->with('success', trans($this->wrapNamespace('core.messages.section.created'), ['title' => $section->name]));
	}

	/**
	 * @param SectionRepository $repository
	 * @param integer $sectionId
	 *
	 * @throws SectionException
	 */
	public function getEdit(SectionRepository $repository, $sectionId)
	{
		$section = $repository->findOrFail($sectionId);

		$this->breadcrumbs
			->add($section->getName(), route('backend.datasource.list', $section->getId()));

		$this->setTitle(trans($this->wrapNamespace('core.title.edit'), ['name' => $section->getName()]));

		$this->setContent($section->getType()->getEditTemplate(), [
			'section' => $section,
			'fields' => $section->getFields()
		]);
	}

	/**
	 * @param SectionRepository $repository
	 * @param $sectionId
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit(SectionRepository $repository, $sectionId)
	{
		$data = $this->request->except(['type']);
		$repository->validateOnUpdate($data);
		$section = $repository->update($sectionId, $data);

		return $this->smartRedirect([$section->getId()])
			->with('success', trans($this->wrapNamespace('core.messages.section.updated'), ['title' => $section->getName()]));
	}

	/**
	 * @param SectionRepository $repository
	 * @param integer $sectionId
	 */
	public function getRemove(SectionRepository $repository, $sectionId)
	{
		$repository->delete($sectionId);
	}
}