<?php namespace KodiCMS\CMS\Helpers;

use Assets;
use KodiCMS\CMS\Wysiwyg\DummyFilter;

class WYSIWYG
{
	const TYPE_HTML = 'html';
	const TYPE_CODE = 'code';

	/**
	 * @var array
	 */
	protected static $editors = [];

	/**
	 * @var array
	 */
	protected static $loaded = [];

	/**
	 * @param string $editorId
	 * @param string|null $name
	 * @param string|null $filter
	 * @param string|null $package
	 * @param string $type
	 */
	public static function add($editorId, $name = null, $filter = null, $package = null, $type = self::TYPE_HTML)
	{
		self::$editors[$editorId] = [
			'name' => $name === null ? studly_case($editorId) : $name,
			'type' => $type == self::TYPE_HTML ? self::TYPE_HTML : self::TYPE_CODE,
			'filter' => empty($filter) ? $editorId : $filter,
			'package' => $package === null ? $editorId : $package
		];
	}

	/**
	 * Remove a editor
	 * @param $editorId string
	 */
	public static function remove($editorId)
	{
		if (isset(self::$editors[$editorId]))
		{
			unset(self::$editors[$editorId]);
		}
	}

	/**
	 * @param string $type
	 */
	public static function loadAll($type = null)
	{
		foreach (self::$editors as $editorId => $data)
		{
			if ($type !== null AND is_string($type))
			{
				if ($type != $data['type'])
				{
					continue;
				}
			}

			self::$loaded[$editorId] = $data;
			Assets::package($data['package']);
		}
	}

	/**
	 * Get a instance of a filter
	 * TODO: доработать вызов филтра, добавить интерфейс
	 * @param $editorId
	 * @return Filter_Decorator
	 */
	public static function getFilter($editorId)
	{
		if (isset(self::$editors[$editorId]))
		{
			$data = self::$editors[$editorId];

			if (class_exists($data['filter']))
			{
				return new $data['filter'];
			}
		}

		return new DummyFilter;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public static function htmlSelect($type = null)
	{
		$editors = ['' => trans('cms::core.helpers.not_select')];

		foreach (self::$editors as $editorId => $data)
		{
			if ($type !== null AND is_string($type))
			{
				if ($type != $data['type'])
				{
					continue;
				}
			}

			$editors[$editorId] = $data['name'];
		}

		return $editors;
	}
}