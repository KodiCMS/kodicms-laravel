<?php namespace Plugins\butschster\DSTags\Widget;

use KodiCMS\Datasource\Traits\WidgetDatasource;
use KodiCMS\Datasource\Traits\WidgetDatasourceFields;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use KodiCMS\Widgets\Traits\WidgetCache;
use KodiCMS\Widgets\Widget\Decorator;

class TagsCloud extends Decorator implements WidgetCacheable
{
	use WidgetCache, WidgetDatasource, WidgetDatasourceFields;

	/**
	 * @var string
	 */
	protected $settingsTemplate = 'butschster:dstags::widgets.tagscloud.settings';



	/**
	 * @return array
	 */
	public function defaultSettings()
	{
		return [
			'order_by' => 'count_desc',
			'min_size' => 10,
			'max_size' => 50
		];
	}

	/**
	 * @return array
	 */
	public function getTaggableFields()
	{
		return !$this->getSection()
			? []
			: $this->section->getFields()->getByType('tags')->lists('name', 'id')->all();
	}

	/**
	 * @return array
	 */
	public function prepareSettingsData()
	{
		$fields = $this->getTaggableFields();

		return compact('fields');
	}

	/**
	 * @return array [[array] $tags, [KodiCMS\Datasource\Contracts\SectionInterface] $section]
	 */
	public function prepareData()
	{
		if (is_null($this->field_id)) return [];

		$tags = $this->getTagsList();

		$cloud = [];
		if (count($tags) > 0)
		{
			$fmax = (int) $this->max_size;
			$fmin = (int) $this->min_size;
			$tmin = min($tags);
			$tmax = max($tags);

			($tmin == $tmin) ? $tmax++ : null;

			foreach ($tags as $tag => $frequency)
			{
				$fontSize = floor(($frequency - $tmin) / ($tmax - $tmin) * ($fmax - $fmin) + $fmin);
				$r = $g = 0;
				$b = floor(255 * ($frequency / $tmax));
				$color = '#' . sprintf('%02s', dechex($r)) . sprintf('%02s', dechex($g)) . sprintf('%02s', dechex($b));

				$cloud[$tag] = [
					'count' => $frequency,
					'size' => $fontSize,
					'color' => $color
				];
			}
		}

		return [
			'tags' => $cloud,
			'section' => $this->getSection()
		];
	}

	/**
	 * @return array
	 */
	public function getTagsList()
	{
		$field = $this->getSection()->getFields()->getById($this->field_id);

		$sectionTable = $field->relatedSection->getSectionTableName();
		$pivotTable = $field->getRelatedTable();
		$query = \DB::table($sectionTable)
			->select('name', 'count')
			->join($pivotTable, $field->getRelatedDBKey(), '=', 'id');

		switch ($this->order_by)
		{
			case 'name_asc':
				$query->orderBy('name', 'asc');
				break;
			case 'name_desc':
				$query->orderBy('name', 'desc');
				break;
			case 'count_asc':
				$query->orderBy('count', 'asc');
				break;
			case 'count_desc':
				$query->orderBy('count', 'desc');
				break;
			default:
				$query->orderBy('name', 'asc');
				break;
		}

		return $query->lists('count', 'name');
	}

	/****************************************************************************************************************
	 * Settings
	 ****************************************************************************************************************/

	/**
	 * @param int $size
	 */
	public function setSettingMinSize($size)
	{
		intval($size);

		if($size < 1) $size = 1;

		$this->settings['min_size'] = $size;
	}

	/**
	 * @param int $size
	 */
	public function setSettingMaxSize($size)
	{
		intval($size);

		if($size < 1) $size = 1;

		$this->settings['max_size'] = $size;
	}
}