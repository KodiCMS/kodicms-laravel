<?php namespace KodiCMS\Pages\Model;

use Illuminate\Support\Facades\DB;

class FrontendPage
{

	/**
	 *
	 * @var array
	 */
	private static $_pagesCache = [];

	/**
	 *
	 * @var Frontend
	 */
	private static $_initialPage = NULL;

	/**
	 * @param string $uri
	 * @param bool $includeHidden
	 * @param FrontendPage $parentPage
	 * @return stdClass
	 */
	public static function find($uri, $includeHidden = TRUE, FrontendPage $parentPage = NULL)
	{
		$uri = trim($uri, '/');

		$urls = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);

		if ($parentPage === NULL) {
			$urls = array_merge([''], $urls);
		}

		$url = '';

		$page = new \stdClass;
		$page->id = 0;

		foreach ($urls as $pageSlug) {
			$url = ltrim($url . '/' . $pageSlug, '/');

			if ($page = self::findBySlug($pageSlug, $parentPage, $includeHidden)) {
//				if (!empty($page->behavior)) {
//					$behavior = Behavior::load($page->behavior_id, $page, $url, $uri);
//
//					if ($behavior !== NULL) {
//						$page->_behavior = $behavior;
//						self::$_initial_page = $page;
//
//						return $page;
//					}
//				}
			} else {
				break;
			}

			$parentPage = $page;
		}

		static::$_initialPage = $page;

		return $page;
	}

	/**
	 * @param $field
	 * @param $value
	 * @param FrontendPage $parentPage
	 * @param bool $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findByField($field, $value, FrontendPage $parentPage = NULL, $includeHidden = TRUE)
	{
		$pageCacheId = static::_getCacheId([$field, $value], $parentPage);

		if (isset(static::$_pagesCache[$pageCacheId])) {
			return static::$_pagesCache[$pageCacheId];
		}

		$pageClass = get_called_class();

		$query = DB::select('pages.*')
			->from('pages')
			->where('pages.' . $field, $value)
			->whereIn('status_id', static::getStatuses($includeHidden))
			->take(1);

		if (config('pages::page.checkDate') === TRUE) {
			$query->where('published_on', '<=', DB::raw('NOW()'));
		}

		if (!is_null($parentPage)) {
			$query->where('parent_id', $parentPage->id);
		}

		$foundPage = $query
//			->cache_tags(['pages'])
//			->cached((int)Config::get('cache', 'front_page'))
			->get();

		if (!$foundPage) {
			return FALSE;
		}

		if ($foundPage->parent_id AND $parentPage === NULL) {
			$parentPage = static::findById($foundPage->parent_id);
		}

		// hook to be able to redefine the page class with behavior
//		if ($parentPage instanceof FrontendPage AND !empty($parentPage->behavior)) {
//			// will return Page by default (if not found!)
//			$page_class = Behavior::load_page($parent->behavior_id);
//		}

		// create the object page
		$foundPage = new FrontendPage($foundPage, $parentPage);

		static::$_pagesCache[$pageCacheId] = $foundPage;

		return $foundPage;
	}

	/**
	 * @param $slug
	 * @param FrontendPage $parentPage
	 * @param bool $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findBySlug($slug, FrontendPage $parentPage = NULL, $includeHidden = TRUE)
	{
		return self::findByField('slug', $slug, $parentPage, $includeHidden);
	}

	/**
	 * @param $id
	 * @param bool $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findById($id, $includeHidden = TRUE)
	{
		return self::findByField('id', (int) $id, NULL, $includeHidden);
	}

	/**
	 * @param $slug
	 * @param FrontendPage $parentPage
	 * @return string
	 */
	final protected static function _getCacheId($slug, FrontendPage $parentPage = NULL)
	{
		if (is_array($slug))
		{
			$slug = implode('::', $slug);
		}

		return $slug . (int) $parentPage;
	}

	/**
	 * @param array $pageData
	 * @param FrontendPage $parentPage
	 */
	public function __construct(array $pageData, FrontendPage $parentPage = NULL)
	{
		$this->_parentPage = $parentPage;

//		foreach ($pageData as $key => $value)
//		{
//			$this->$key = $value;
//		}

		if ($this->parentPage() instanceof FrontendPage) {
			$this->_setURL();
		}

		$this->level = $this->getLevel();
	}


	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->id();
	}
}
