<?php namespace KodiCMS\Userguide\Http\Controllers;

use Cache;
use ModulesFileSystem;
use KodiCMS\Userguide\Markdown;
use KodiCMS\CMS\Exceptions\Exception;
use KodiCMS\CMS\Http\Controllers\System\BackendController;

class UserguideController extends BackendController
{
	/**
	 * @var string
	 */
	public $moduleNamespace = 'userguide::';

	public function before()
	{
		if (!class_exists('\Michelf\Markdown'))
		{
			throw new Exception('Markdown parser not found. Live documentation will not work in your environment.');
		}

		parent::before();

		$this->setTitle(trans('userguide::core.title'));
	}

	public function getIndex()
	{
		$modules = $this->modules();
		$this->setContent('index', compact('modules'));
	}

	/**
	 * @param string $module
	 * @param string|null $page
	 */
	public function getModule($module, $page = null)
	{
		if (!config('userguide.modules.' . $module . '.enabled'))
		{
			abort(404, 'That module doesn\'t exist, or has userguide pages disabled.');
		}

		if (is_null($page))
		{
			$page = config('userguide.modules.' . $module . '.index_page', 'index');
		}

		$title = config('userguide.modules.' . $module . '.name');

		// Find the markdown file for this page
		$file = $this->file($module . '/' . $page);

		// If it's not found, show the error page
		if ( ! $file)
		{
			abort(404, 'Userguide page not found');
		}

		Markdown::$baseUrl = route('backend.userguide.docs', [$module]);

		$content = Cache::remember("guide:{$module}:{$page}", 60, function () use ($file)
		{
			return Markdown::defaultTransform(file_get_contents($file));
		});

		$menuItems =  Cache::remember("guide:{$module}:menu", 60, function () use ($module)
		{
			return Markdown::defaultTransform($this->getAllMenuMarkdown($module));
		});

		$menu = view('userguide::menu', compact('menuItems', 'title'));

		$this->setTitle($title, Markdown::$baseUrl);
		$this->setTitle($this->title($module, $page));

		$this->setContent('doc', compact('title', 'menu', 'content'));
	}

	/**
	 * @param string $page
	 *
	 * @return string
	 */
	public function file($page)
	{
		return ModulesFileSystem::findFile('guide', $page, 'md');
	}

	/**
	 * @param string $module
	 * @param string $page
	 *
	 * @return string
	 */
	public function section($module, $page)
	{
		$markdown = $this->getAllMenuMarkdown($module);

		if (preg_match('~\*{2}(.+?)\*{2}[^*]+\[[^\]]+\]\(' . preg_quote($page) . '\)~mu', $markdown, $matches))
		{
			return $matches[1];
		}

		return $page;
	}

	/**
	 * @param string $module
	 * @param string $page
	 *
	 * @return string
	 */
	public function title($module, $page)
	{
		$markdown = $this->getAllMenuMarkdown($module);

		if (preg_match('~\[([^\]]+)\]\(.*'.preg_quote($page).'\)~mu', $markdown, $matches))
		{
			// Found a title for this link
			return $matches[1];
		}

		return $page;
	}

	/**
	 * @param string $module
	 *
	 * @return string
	 */
	protected function getAllMenuMarkdown($module)
	{
		// Only do this once per request...
		static $markdown = '';

		if (empty($markdown))
		{
			// Get menu items
			$file = $this->file($module . '/documentation');

			if ($file AND $text = file_get_contents($file))
			{
				$markdown .= $text;
			}

		}

		return $markdown;
	}

	/**
	 * Get the list of modules from the config,
	 * and reverses it so it displays in the order
	 * the modules are added, but move Kohana to the top.
	 *
	 * @return array
	 */
	protected function modules()
	{
		$modules = array_reverse(config('userguide.modules'));

		if (isset($modules['laravel']))
		{
			$laravel = $modules['laravel'];
			unset($modules['laravel']);
			$modules = array_merge(['laravel' => $laravel], $modules);
		}

		// Remove modules that have been disabled via config
		foreach ($modules as $key => $value)
		{
			if (!config('userguide.modules.' . $key . '.enabled'))
			{
				unset($modules[$key]);
			}
		}

		return $modules;
	}
}