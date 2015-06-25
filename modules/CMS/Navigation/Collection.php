<?php namespace KodiCMS\CMS\Navigation;


/**
 * Class Collection
 * TODO: убрать статику. Greabock 20.05.2015
 *
 * @package KodiCMS\CMS\Navigation
 */
class Collection
{

	/**
	 * @var Section
	 */
	protected static $rootSection = NULL;

	/**
	 * @var Page
	 */
	protected static $currentPage = NULL;

	/**
	 * @param $uri
	 * @param array $items
	 * @return Section
	 */
	public static function init($uri, array $items)
	{
		static::build($items);
		static::getRootSection()->findActivePageByUri(strtolower($uri));
		static::getRootSection()->sort();

		return static::getRootSection();
	}

	/**
	 * @return Page
	 */
	public static function getCurrentPage()
	{
		return static::$currentPage;
	}

	/**
	 * @param array $items
	 */
	protected static function build(array $items)
	{
		foreach ($items as $section) {
			if (!isset($section['name'])) {
				continue;
			}

			if (isset($section['url'])) {
				$sectionObject = self::getRootSection();

				$page = new Page($section);
				$sectionObject->addPage($page);
			} else {
				$sectionObject = self::getSection($section['name']);
				$sectionObject->setAttribute(array_except($section, ['children']));

				if (!empty($section['children'])) {
					$sectionObject->addPages($section['children']);
				}
			}
		}
	}

	/**
	 * @param $name
	 * @param Section $parent
	 * @param int $priority
	 * @return Section
	 */
	public static function getSection($name, Section $parent = NULL, $priority = 1)
	{
		if ($parent === NULL) {
			$parent = self::getRootSection();
		}

		$section = $parent->findSection($name);

		if ($section === NULL) {
			$section = new Section([
				'name' => $name,
				'priority' => $priority
			]);

			$parent->addPage($section);
		}

		return $section;
	}

	/**
	 * @return void
	 */
	public static function setRootSection()
	{
		static::$rootSection = new Section([
			'name' => 'root'
		]);
	}

	/**
	 * @return Section
	 */
	public static function getRootSection()
	{
		if (static::$rootSection === NULL) {
			static::setRootSection();
		}

		return static::$rootSection;
	}

	/**
	 * @param string $section
	 * @param $name
	 * @param $uri
	 * @param int $priority
	 * @return $this
	 */
	public static function addSection($section = 'Other', $name, $uri, $priority = 0)
	{
		return static::getSection($section)
			->addPage(new Page([
				'name' => $name,
				'url' => $uri
			]), $priority);
	}

	/**
	 * @param string $uri
	 * @param array $data
	 */
	public static function update($uri, array $data)
	{
		$page = self::findPageByUri($uri);

		if ($page instanceof Page) {
			foreach ($data as $key => $value) {
				$page->{$key} = $value;
			}
		}
	}

	/**
	 * @param string $uri
	 * @return null|Page
	 */
	public static function & findPageByUri($uri)
	{
		foreach (static::getRootSection()->getSections() as $section) {
			if ($page = $section->findPageByUri($uri)) {
				return $page;
			}
		}

		return NULL;
	}

	/**
	 * @return void
	 */
	public static function sort()
	{
		uasort(self::getRootSection()->getSections(), function ($a, $b) {
			if ($a->id() == $b->id()) {
				return 0;
			}

			return ($a->id() < $b->id()) ? -1 : 1;
		});
	}

	/**
	 * @param Page $page
	 */
	public static function setCurrentPage(Page & $page)
	{
		static::$currentPage = $page;
	}
}