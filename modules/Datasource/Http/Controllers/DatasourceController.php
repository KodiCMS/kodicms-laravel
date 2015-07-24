<?php namespace KodiCMS\Datasource\Http\Controllers;

use DatasourceManager;
use KodiCMS\Datasource\Repository\SectionRepository;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class DatasourceController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'datasource::';

	/**
	 * @param SectionRepository $repository
	 * @param integer $dsId
	 */
	public function getIndex(SectionRepository $repository, $dsId)
	{
		$section = $repository->findOrFail($dsId);

		$this->setTitle($section->getName());

		$this->setContent('content', [
			'navigation' => view('datasource::navigation', [
				'types' => DatasourceManager::getAvailableTypes(),
				'sections' => DatasourceManager::getSections()
			]),
			'section' => view('datasource::section', [
				'headline' => $section->getHeadline()->render(),
				'toolbar' => $section->getToolbar()->render(),
				'section' => $section
			])
		]);

		view()->share('sectionModel', $section);

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

		if (is_null($typeObject = DatasourceManager::getTypeObject($type)))
		{
			throw new SectionException("Datasource type {$type} not found");
		}

		$section = $repository->instance();
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
	 * @param integer $dsId
	 *
	 * @throws SectionException
	 */
	public function getEdit(SectionRepository $repository, $dsId)
	{
		$section = $repository->findOrFail($dsId);

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
	 * @param $dsId
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit(SectionRepository $repository, $dsId)
	{
		$data = $this->request->except(['type']);
		$repository->validateOnUpdate($data);
		$section = $repository->update($dsId, $data);

		return $this->smartRedirect([$section->getId()])
			->with('success', trans($this->wrapNamespace('core.messages.section.updated'), ['title' => $section->getName()]));
	}
}