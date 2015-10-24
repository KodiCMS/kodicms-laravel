<?php namespace KodiCMS\Pages\Helpers;

use HTML;
use Assets;
use KodiCMS\Pages\Model\FrontendPage;

class Meta
{
	/**
	 * @var FrontendPage
	 */
	protected $page = null;

	/**
	 * Конструктор
	 *
	 * При передачи объекта страницы в нем генерируется
	 *
	 *        <title>...</title>
	 *        <meta name="keywords" content="" />
	 *        <meta name="description" content="" />
	 *        <meta name="robots" content="" />
	 *        <meta name="robots" content="" />
	 *        <meta charset="utf-8">
	 *
	 * @param FrontendPage $page
	 */
	public function __construct(FrontendPage $page = null)
	{
		if (!is_null($page))
		{
			$this->setPage($page, true);
		}
	}

	/**
	 * @param FrontendPage $page
	 * @param bool $setPageData
	 * @return $this
	 */
	public function setPage(FrontendPage $page, $setPageData = false)
	{
		$this->page = $page;

		if ($setPageData !== false)
		{
			$this
				->setTitle(e($this->page->getMetaTitle()))
				->addMeta(['name' => 'keywords', 'content' => e($this->page->getMetaKeywords())])
				->addMeta(['name' => 'description', 'content' => e($this->page->getMetaDescription())])
				->addMeta(['name' => 'robots', 'content' => e($this->page->getMetaRobots())])
				->addMeta(['charset' => 'utf-8'], 'meta::charset');
		}

		return $this;
	}

	/**
	 * @param string $title
	 * @return mixed
	 */
	public function setTitle($title)
	{
		return $this->addToGroup('title', '<title>:title</title>', [
			':title' => e($title)
		]);
	}


	/**
	 * @param array $attributes
	 * @param null|string $group
	 * @return $this
	 */
	public function addMeta(array $attributes, $group = null)
	{
		$meta = "<meta" . HTML::attributes($attributes) . " />";

		if ($group === null)
		{
			if (isset($attributes['name']))
			{
				$group = $attributes['name'];
			}
			else
			{
				$group = str_random();
			}
		}

		return $this->addToGroup($group, $meta);
	}

	/**
	 * @param string $filename [default: css/all.css]
	 * @param null|string $dependency
	 * @param array|null $attrs
	 * @return $this
	 */
	public function addCssElixir($filename = 'css/all.css', $dependency = null, array $attrs = null)
	{
		return $this->addCss('elixir.css', elixir($filename), $dependency, $attrs);
	}

	/**
	 * @param string $handle
	 * @param string $src
	 * @param null|string $dependency
	 * @param null|array $attrs
	 * @return $this
	 */
	public function addCss($handle, $src, $dependency = null, array $attrs = null)
	{
		Assets::css($handle, $src, $dependency, $attrs);
		return $this;
	}

	/**
	 * @param null|string $handle
	 * @return $this
	 */
	public function removeCss($handle = NULL)
	{
		Assets::removeCss($handle);
		return $this;
	}

	/**
	 * @param string $filename [default: js/app.js]
	 * @param null|string $dependency
	 * @param bool $footer
	 * @return $this
	 */
	public function addJsElixir($filename = 'js/app.js', $dependency = null, $footer = false)
	{
		return $this->AddJs('elixir.js', elixir($filename), $dependency, $footer);
	}

	/**
	 * @param string $handle
	 * @param string $src
	 * @param null|string $dependency
	 * @param bool $footer
	 * @return $this
	 */
	public function AddJs($handle, $src, $dependency = NULL, $footer = false)
	{
		Assets::js($handle, $src, $dependency, $footer);
		return $this;
	}

	/**
	 * @param null|string $handle
	 * @return $this
	 */
	public function removeJs($handle = NULL)
	{
		Assets::removeJs($handle);
		return $this;
	}

	/**
	 * Указание favicon
	 *
	 * @param string $url
	 * @param string $rel
	 * @return  $this
	 */
	public function setFavicon($url, $rel = 'shortcut icon')
	{
		return $this->addToGroup('icon', '<link rel=":rel" href=":url" type="image/x-icon" />', [
			':url' => e($url),
			':rel' => e($rel)
		]);
	}

	/**
	 * @param string $handle
	 * @param string $content
	 * @param array $params
	 * @param null|string $dependency
	 * @return $this
	 */
	public function addToGroup($handle, $content, $params = [], $dependency = null)
	{
		Assets::group('FRONTEND', $handle, strtr($content, $params), $dependency);
		return $this;
	}


	/**
	 * @param string|null $handle
	 * @return $this
	 */
	public function removeFromGroup($handle = null)
	{
		Assets::removeGroup('FRONTEND', $handle);
		return $this;
	}

	/**
	 * @param string|array $name
	 * @param bool $loadDependencies
	 * @param bool $footer
	 * @return $this
	 */
	public function addPackage($name, $loadDependencies = false, $footer = false)
	{
		Assets::package($name, $loadDependencies, $footer);
		return $this;
	}

	/**
	 * @return string
	 */
	public function build()
	{
		return Assets::group('FRONTEND') . Assets::css() . Assets::js();
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->build();
	}
}