<?php namespace KodiCMS\Datasource\Widget;

use Illuminate\Support\Collection;
use KodiCMS\Widgets\Widget\Decorator;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Traits\WidgetPaginator;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Datasource\Traits\WidgetDatasource;
use KodiCMS\Datasource\Contracts\SectionInterface;
use KodiCMS\Datasource\Repository\SectionRepository;

class DatasourceList extends Decorator implements WidgetCacheable
{
	use WidgetCache, WidgetPaginator, WidgetDatasource;

	/**
	 * @var SectionRepository
	 */
	protected $sectionRepository;

	/**
	 * @var SectionInterface|null
	 */
	protected $section;

	/**
	 * @var array|null
	 */
	protected $documents = null;

	/**
	 * @var string
	 */
	protected $settingsTemplate = 'datasource::widgets.list.settings';

	/**
	 * @param SectionRepository $repository
	 */
	public function boot(SectionRepository $repository)
	{
		$this->sectionRepository = $repository;
	}

	/**
	 * @return array
	 */
	public function getSelectedFields()
	{
		return (array) $this->selected_fields;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Model|SectionInterface|null
	 */
	public function getSection()
	{
		if (is_null($this->section) and $this->isDatasourceSelected())
		{
			$this->section = $this->sectionRepository->findOrFail($this->getSectionId());
		}

		return $this->section;
	}

	/**
	 * @return array
	 */
	public function prepareSettingsData()
	{
		$fields = !$this->getSection() ? [] : $this->section->getFields();
		$ordering = (array) $this->ordering;

		return compact('fields', 'ordering');
	}

	/**
	 * @param array $filters
	 */
	public function setSettingFilters(array $filters)
	{
		$data = [];
		foreach ($filters as $key => $rows)
		{
			foreach ($rows as $i => $row)
			{
				$data[$i][$key] = $row;
			}
		}

		$this->settings['filters'] = $data;
	}

	/**
	 * @return array [[Collection] $documents, [Collection] $documentsRaw, [KodiCMS\Datasource\Contracts\SectionInterface] $section, [Illuminate\Pagination\LengthAwarePaginator] $pagination]
	 */
	public function prepareData()
	{
		if (is_null($this->getSection()))
		{
			return [];
		}

		$result = $this->getDocuments();

		$visibleFields = [];

		foreach ($this->getSection()->getFields() as $field)
		{
			if (in_array($field->getDBKey(), $this->getSelectedFields()))
			{
				$visibleFields[] = $field;
			}
		}

		$documents = [];

		foreach ($result as $document)
		{
			foreach ($visibleFields as $field)
			{
				$doc[$field->getDBKey()] = $document->getWidgetValue($field->getDBKey());
			}

			$doc['href'] = strtr($this->document_uri, $this->buildUrlParams($doc));

			$documents[$document->getId()] = $doc;
		}

		return [
			'section' => $this->getSection(),
			'documentsRaw' => $result->items(),
			'pagination' => $result,
			'documents' => new Collection($documents)
		];
	}

	/**
	 * @param int $recurse
	 * @return array|null
	 */
	protected function getDocuments($recurse = 3)
	{
		if (!is_null($this->documents))
		{
			return [];
		}

		if ($this->order_By_rand)
		{
			$this->ordering = [];
		}

		$documents = $this->getSection()
			->getEmptyDocument()
			->getDocuments($this->selected_fields, (array) $this->ordering, (array) $this->filters);

		if ($this->order_By_rand)
		{
			$documents->orderByRaw('RAND()');
		}

		// TODO добавить кол-во выводимых документов
		return $documents->paginate();
	}

	/**
	 *
	 * @param array $data
	 * @param string $preffix
	 * @return array
	 */
	public function buildUrlParams(array $data, $preffix = null)
	{
		$params = [];

		foreach ($data as $field => $value)
		{
			if (is_array($value))
			{
				$params += $this->buildUrlParams($value, $field);
			}
			else
			{
				$field = $preffix === null
					? $field
					: $preffix . '.' . $field;

				$params[':' . $field] = $value;
			}
		}

		return $params;
	}
}