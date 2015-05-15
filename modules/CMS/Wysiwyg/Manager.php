<?php namespace KodiCMS\CMS\Wysiwyg;

use Assets;
use KodiCMS\CMS\Contracts\WysiwygFilterInterface;

class Manager
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
		static::$editors[$editorId] = [
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
		if (isset(static::$editors[$editorId]))
		{
			unset(static::$editors[$editorId]);
		}
	}

	/**
	 * @param string $type
	 */
	public static function loadAll($type = null)
	{
		foreach (static::$editors as $editorId => $data)
		{
			if ($type !== null AND is_string($type))
			{
				if ($type != $data['type'])
				{
					continue;
				}
			}

			static::$loaded[$editorId] = $data;
			Assets::package($data['package']);
		}
	}

	/**
	 * Get a instance of a filter
	 * TODO: доработать вызов филтра, добавить интерфейс
	 * @param $editorId
	 * @return WysiwygFilterInterface
	 */
	public static function getFilter($editorId)
	{
		if (isset(static::$editors[$editorId]))
		{
			$data = static::$editors[$editorId];

			if (class_exists($data['filter']) and in_array('KodiCMS\CMS\Contracts\WysiwygFilterInterface', class_implements($data['filter'])))
			{
				return new $data['filter'];
			}
		}

		return new WysiwygDummyFilter;
	}

	/**
	 * @param string $editorId
	 * @param string $text
	 * @return string
	 */
	public static function applyFilter($editorId, $text)
	{
		return static::getFilter($editorId)->apply($text);
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public static function htmlSelect($type = null)
	{
		$editors = ['' => trans('cms::core.helpers.not_select')];

		foreach (static::$editors as $editorId => $data)
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