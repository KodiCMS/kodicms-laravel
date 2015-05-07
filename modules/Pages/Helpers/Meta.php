<?php namespace KodiCMS\Pages\Helpers;

use HTML;
use KodiCMS\Pages\Model\FrontendPage;
use Assets;

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
	 * Для переопеределения данных используйте
	 *
	 *        Meta::factory($page)->add(array('name' => 'description', ...));
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
	 * @param string $handle
	 * @param string $src
	 * @param null|string $deps
	 * @param null|array $attrs
	 * @return $this
	 */
	public function addCss($handle, $src, $deps = null, array $attrs = null)
	{
		Assets::css($handle, $src, $deps, $attrs);

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
	 * @param string $handle
	 * @param string $src
	 * @param null|string $deps
	 * @param bool $footer
	 * @return $this
	 */
	public function AddJs($handle, $src, $deps = NULL, $footer = FALSE)
	{
		Assets::js($handle, $src, $deps, $footer);
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
	 * @param null|string $deps
	 * @return $this
	 */
	public function addToGroup($handle, $content, $params = [], $deps = null)
	{
		Assets::group('FRONTEND', $handle, strtr($content, $params), $deps);

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
	 * @param bool $footer
	 * @return $this
	 */
	public function addPackage($name, $footer = false)
	{
		Assets::package($name, $footer);

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