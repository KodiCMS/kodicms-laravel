<?php namespace KodiCMS\CMS\Wysiwyg;

use Assets;
use ReflectionClass;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use KodiCMS\CMS\Contracts\WysiwygFilterInterface;


class Manager
{
	const TYPE_HTML = 'html';
	const TYPE_CODE = 'code';


	/**
	 * @var Application
     */
	protected $app;

	/**
	 *
	 * @var
     */
	protected $config;

	/**
	 * Available wysiwyg editors
	 *
	 * @var array
	 */
	protected  $available = [];

	/**
	 * Loaded wysiwyg editors
	 *
	 * @var array
	 */
	protected  $loaded = [];

	/**
	 * @param Application $app
     */
	public function __construct(Application $app)
	{
		$this->app = $app;
		$this->config = $this->app['config'];
	}

	/**
	 * @param string $editorId
	 * @param string | null $name
	 * @param string | null $filter
	 * @param string | null $package
	 * @param string $type
	 */
	public function add($editorId, $name = null, $filter = null, $package = null, $type = self::TYPE_HTML)
	{
		$this->available[$editorId] = [
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
		if (isset($this->loaded[$editorId])) unset($this->loaded[$editorId]);

		if (isset($this->available[$editorId])) unset($this->available[$editorId]);

	}


	/**
	 * @param string $type
	 */
	public function loadAll($type = null)
	{
		foreach ($this->available as $editorId => $data)
		{
			if ( ! is_null($type) AND is_string($type))
			{
				if ($type != $data['type']) continue;
			}

			$this->load($editorId);
		}
	}

	/**
	 * @param string | null $type
     */
	public function loadDefault($type = null)
	{
		if(is_null($type))
		{
			$this->load([
				$this->config['default_html_editor'],
				$this->config['default_code_editor']
			]);

			return;
		}

		$editorId = ($type === self::TYPE_HTML) ? $this->config['default_html_editor'] : $this->config['default_code_editor'];

		$this->load($editorId);

	}

	/**
	 * @param string|null $editorId
	 * @return array|bool
     */
	public function loaded($editorId = null)
	{
		if(is_null($editorId)) return $this->loaded;

		return isset($this->loaded[$editorId]);
	}


	/**
	 * @param $editorId
	 * @return bool
     */
	public function exists($editorId)
	{
		return isset($this->available[$editorId]);
	}

	/**
	 * @param $editorId
     */
	public function load($editorIds)
	{

		if(is_array($editorIds))
		{
			foreach($editorIds as $editorId)
			{
				$this->boot($editorId);
			}

			return;
		}

		$this->loaded[$editorIds] = $this->available[$editorIds];

		Assets::package($this->loaded[$editorIds]['package']);
	}

	/**
	 * @param $editorId
     */
	protected function boot($editorId)
	{
		if($this->exists($editorId) and ! $this->loaded($editorId))
		{
			$this->loaded[$editorId] = $this->available[$editorId];

			Assets::package($this->loaded[$editorId]['package']);
		}
	}


	/**
	 * Get a instance of a filter
	 * TODO: доработать вызов филтра, добавить интерфейс
	 * @param $editorId
	 * @return WysiwygFilterInterface
	 */
	public function getFilter($editorId)
	{
		if (isset($this->available[$editorId]))
		{
			$data = $this->available[$editorId];

			if ( class_exists($data['filter']) and (new ReflectionClass($data['filter']))->implementsInterface(WysiwygFilterInterface::class))
			{
				return $this->app->make($data['filter']);
			}
		}

		return $this->app->make(WysiwygDummyFilter::class);
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

		foreach ($this->available as $editorId => $data)
		{
			if ( ! is_null($type) AND is_string($type))
			{
				if ($type != $data['type']) continue;
			}

			$editors[$editorId] = $data['name'];
		}

		return $editors;
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

}