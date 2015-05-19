<?php namespace KodiCMS\CMS\Wysiwyg;

use Assets;
use Config;
use Illuminate\Contracts\Foundation\Application;
use KodiCMS\CMS\Contracts\WysiwygFilterInterface;

class Manager
{
	const TYPE_HTML = 'html';
	const TYPE_CODE = 'code';

	/**
	 * @var array
	 */
	protected  $editors = [];

	/**
	 * @var array
	 */
	protected  $loaded = [];


	/**
	 * @param Application $app
     */
	public function __construct()
	{
	}


	/**
	 * @param string $editorId
	 * @param string|null $name
	 * @param string|null $filter
	 * @param string|null $package
	 * @param string $type
	 */
	public function add($editorId, $name = null, $filter = null, $package = null, $type = self::TYPE_HTML)
	{
		$this->editors[$editorId] = [
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
	public function remove($editorId)
	{
		if (isset($this->editors[$editorId]))
		{
			unset($this->editors[$editorId]);
		}
	}


	/**
	 * @param string $type
	 */
	public function loadAll($type = null)
	{
		foreach ($this->editors as $editorId => $data)
		{
			if ($type !== null AND is_string($type))
			{
				if ($type != $data['type'])
				{
					continue;
				}
			}

			$this->load($editorId);
		}
	}


	/**
	 *
     */
	public function loadDefault()
	{
		$editorId = Config::get('default_html_editor');

		if( ! $this->isLoaded($editorId)) $this->load($editorId);

	}


	/**
	 * @param string|null $editorId
	 * @return array|bool
     */
	public function loaded($editorId = null)
	{
		if(is_null($editorId)) return $this->loaded;

		return array_key_exists($editorId, $this->loaded);
	}


	/**
	 * @param $editorId
	 * @return bool
     */
	public function exists($editorId)
	{
		return array_key_exists($editorId, $this->editors);
	}

	/**
	 * @param $editorId
     */
	public function load($editorId)
	{
		$this->loaded[$editorId] = $this->editors[$editorId];

		Assets::package($this->loaded[$editorId]['package']);
	}


	/**
	 * Get a instance of a filter
	 * TODO: доработать вызов филтра, добавить интерфейс
	 * @param $editorId
	 * @return WysiwygFilterInterface
	 */
	public function getFilter($editorId)
	{
		if (isset($this->editors[$editorId]))
		{
			$data = $this->editors[$editorId];

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
	public function applyFilter($editorId, $text)
	{
		return $this->getFilter($editorId)->apply($text);
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function htmlSelect($type = null)
	{
		$editors = ['' => trans('cms::core.helpers.not_select')];

		foreach ($this->editors as $editorId => $data)
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


	/**
	 * @return string
     */
	public function code()
	{
		return static::TYPE_CODE;
	}

	/**
	 * @return string
     */
	public function html()
	{
		return static::TYPE_HTML;
	}
}