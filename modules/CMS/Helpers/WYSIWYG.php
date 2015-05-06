<?php namespace KodiCMS\CMS\Helpers;

use Assets;
use KodiCMS\Pages\Filter\Dummy as Filter;

class WYSIWYG
{
	const TYPE_HTML = 'html';
	const TYPE_CODE = 'code';

	/**
	 *
	 * @var array
	 */
	protected static $editors = [];

	/**
	 *
	 * @var array
	 */
	protected static $loaded = [];

	/**
	 *
	 * @param string $editorId
	 * @param string $name
	 * @param string $filter
	 * @param string $package
	 * @param string $type
	 */
	public static function add($editorId, $name = NULL, $filter = NULL, $package = NULL, $type = self::TYPE_HTML)
	{
		self::$editors[$editorId] = [
			'name' => $name === NULL ? \Str::studly($editorId) : $name,
			'type' => $type == self::TYPE_HTML ? self::TYPE_HTML : self::TYPE_CODE,
			'filter' => empty($filter) ? $editorId : $filter,
			'package' => $package === NULL ? $editorId : $package
		];
	}

	/**
	 * Remove a editor
	 *
	 * @param $editorId string
	 */
	public static function remove($editorId)
	{
		if (isset(self::$editors[$editorId])) {
			unset(self::$editors[$editorId]);
		}
	}

	/**
	 *
	 * @param string $type
	 */
	public static function loadAll($type = NULL)
	{
		foreach (self::$editors as $editorId => $data) {
			if ($type !== NULL AND is_string($type)) {
				if ($type != $data['type']) {
					continue;
				}
			}

			self::$loaded[$editorId] = $data;
			Assets::package($data['package']);
		}
	}

	/**
	 * Get a instance of a filter
	 *
	 * @param $editorId
	 *
	 * @return Filter_Decorator
	 */
	public static function getFilter($editorId)
	{
		if (isset(self::$editors[$editorId])) {
			$data = self::$editors[$editorId];

			if (class_exists($data['filter'])) {
				return new $data['filter'];
			}
		}

		return new Filter;
	}

	/**
	 *
	 * @param string $type
	 * @return array
	 */
	public static function htmlSelect($type = NULL)
	{
		$editors = ['' => trans('cms::core.helpers.not_select')];

		foreach (self::$editors as $editorId => $data) {
			if ($type !== NULL AND is_string($type)) {
				if ($type != $data['type']) {
					continue;
				}
			}

			$editors[$editorId] = $data['name'];
		}

		return $editors;
	}
}