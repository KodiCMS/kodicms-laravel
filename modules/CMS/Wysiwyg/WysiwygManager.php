<?php namespace KodiCMS\CMS\Wysiwyg;

use KodiCMS\CMS\Exceptions\WysiwygException;
use Illuminate\Contracts\Foundation\Application;

class WysiwygManager {

	const TYPE_HTML = 'html';
	const TYPE_CODE = 'code';

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var
	 */
	protected $config;

	/**
	 * Available wysiwyg editors
	 *
	 * @var array
	 */
	protected $available = [];

	/**
	 * @param Application $app
	 */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->config = $this->app['config'];
	}

	/**
	 * @return string|null
	 */
	public function getDefaultHTMLEditor()
	{
		return $this->config['cms.default_html_editor'];
	}

	/**
	 * @return string|null
	 */
	public function getDefaultCodeEditor()
	{
		return $this->config['cms.default_code_editor'];
	}

	/**
	 * Return TYPE_CODE constant
	 *
	 * @return string
	 */
	public function code()
	{
		return static::TYPE_CODE;
	}

	/**
	 * Return TYPE_HTML constant
	 *
	 * @return string
	 */
	public function html()
	{
		return static::TYPE_HTML;
	}

	/**
	 * @param $editorId
	 * @return bool
	 */
	public function isExists($editorId)
	{
		return isset($this->available[$editorId]);
	}

	/**
	 * @param string $editorId
	 * @return bool
	 */
	public function isLoaded($editorId)
	{
		if (!is_null($editor = $this->getEditor($editorId)))
		{
			return $editor->isUsed();
		}

		return false;
	}

	/**
	 * @return array
	 */
	public function getUsed()
	{
		$editors = [];
		foreach ($this->getAvailable() as $editorId => $editor)
		{
			if ($editor->isUsed())
			{
				$editors[$editorId] = $editor;
			}
		}

		return $editors;
	}

	/**
	 * @param string $editorId
	 * @param string|null $name
	 * @param string|null $filter
	 * @param string|null $package
	 * @param string $type
	 * @return $this
	 */
	public function add($editorId, $name = null, $filter = null, $package = null, $type = self::TYPE_HTML)
	{
		$this->available[$editorId] = new WysiwygEditor($editorId, $name, $filter, $package, $type);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAvailable()
	{
		return $this->available;
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function getAvailableByType($type)
	{
		$editors = [];

		foreach ($this->getAvailable() as $editorId => $editor)
		{
			if ($type == $editor->getType())
			{
				$editors[$editorId] = $editor;
			}
		}

		return $editors;
	}

	/**
	 * @param $editorId
	 * @return WysiwygEditor|null
	 */
	public function getEditor($editorId)
	{
		return array_get($this->getAvailable(), $editorId);
	}

	/**
	 * Remove a editor
	 *
	 * @param $editorId string
	 */
	public function remove($editorId)
	{
		if ($this->isLoaded($editorId))
		{
			unset($this->loaded[$editorId]);
		}

		if (isset($this->available[$editorId]))
		{
			unset($this->available[$editorId]);
		}
	}

	public function loadAllEditors()
	{
		foreach($this->getAvailable() as $editorId => $editor)
		{
			$this->loadEditor($editorId);
		}
	}

	/**
	 * @param string $type
	 */
	public function loadEditorsByType($type)
	{
		$editors = $this->getAvailableByType($type);

		foreach($editors as $editorId => $editor)
		{
			$this->loadEditor($editorId);
		}
	}

	/**
	 * @param string|null $type
	 */
	public function loadDefaultEditors($type = null)
	{
		if (is_null($type))
		{
			$defaultEditors = [
				$this->getDefaultHTMLEditor(),
				$this->getDefaultCodeEditor()
			];

			foreach($defaultEditors as $editorId)
			{
				$this->loadEditor($editorId);
			}

			return;
		}

		$editorId = ($type === static::TYPE_HTML)
			? $this->getDefaultHTMLEditor()
			: $this->getDefaultCodeEditor();

		$this->loadEditor($editorId);
	}

	/**
	 * @param string $editorId
	 * @return bool
	 */
	public function loadEditor($editorId)
	{
		if (is_null($editorId))
		{
			return false;
		}

		if (!is_null($editor = $this->getEditor($editorId)))
		{
			if ($editor->isUsed())
			{
				return true;
			}

			return $editor->load();
		}

		return false;
	}


	/**
	 * @param string $editorId
	 * @param string $text
	 * @return string string
	 * @throws WysiwygException
	 */
	public function applyFilter($editorId, $text)
	{
		if (!is_null($editor = $this->getEditor($editorId)))
		{
			return $editor->applyFilter($text);
		}

		throw new WysiwygException("Editor [{$editorId}] not found");
	}

	/**
	 * @param string $type
	 * @return array
	 */
	public function htmlSelect($type = null)
	{
		$options = ['' => trans('cms::core.helpers.not_select')];

		$editors = is_null($type)
			? $this->getAvailable()
			: $this->getAvailableByType($type);

		foreach ($editors as $editorId => $editor)
		{
			$options[$editorId] = $editor->getName();
		}

		return $options;
	}
}