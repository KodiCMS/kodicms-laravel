<?php namespace KodiCMS\Pages\Model;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use KodiCMS\CMS\Helpers\File;
use KodiCMS\CMS\Helpers\Text;

class FrontendPage
{
	const STATUS_DRAFT     = 1;
	const STATUS_PUBLISHED = 100;
	const STATUS_HIDDEN    = 101;

	/**
	 *
	 * @var array
	 */
	private static $_pagesCache = [];

	/**
	 *
	 * @var FrontendPage
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

		$pageObject = new \stdClass;
		$pageObject->id = 0;

		foreach ($urls as $pageSlug) {
			$url = ltrim($url . '/' . $pageSlug, '/');

			if ($pageObject = self::findBySlug($pageSlug, $parentPage, $includeHidden)) {

//				if (!is_null($pageObject->behavior)) {
//					if (($behavior = BehaviorManager::load($pageObject->behavior, $pageObject, $url, $uri)) !== NULL) {
//
//						$pageObject->_behaviorObject = $behavior;
//						self::$_initialPage = $pageObject;
//
//						return $pageObject;
//					}
//				}
			} else {
				break;
			}

			$parentPage = $pageObject;
		}

		static::$_initialPage = $pageObject;

		return $pageObject;
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
		$pageCacheId = static::getCacheId([$field, $value], $parentPage);

		if (isset(static::$_pagesCache[$pageCacheId])) {
			return static::$_pagesCache[$pageCacheId];
		}

		$pageClass = get_called_class();

		$query = DB::table('pages')
			->where('pages.' . $field, $value)
			->whereIn('status', static::getStatuses($includeHidden));

		if (config('pages::checkDate') === TRUE) {
			$query->where('published_at', '<=', DB::raw('NOW()'));
		}

		if (!is_null($parentPage)) {
			$query->where('parent_id', $parentPage->id);
		}

		// TODO: добавить кеширование запросов
		$foundPage = $query
//			->cacheTags(['frontPage', 'pages'])
//			->remember(config('pages.cache.findByField'))
			->take(1)
			->first();

		if (is_null($foundPage)) {
			return NULL;
		}

		$foundPage = new FrontendPage($foundPage);

		if (!is_null($foundPage->parent_id) AND $parentPage === NULL) {
			$parentPage = static::findById($foundPage->getParentId());
		}

		// hook to be able to redefine the page class with behavior
//		if ($parentPage instanceof FrontendPage AND !is_null($parentPage->getBehavior())) {
//			// will return Page by default (if not found!)
//			$pageClass = BehaviorManager::findPageClass($parentPage->getBehavior());
//		}

		// create the object page
		// TODO Заменить на загрузку класса и behavior
		$foundPage->setParentPage($parentPage);

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
		return self::findByField('id', (int)$id, NULL, $includeHidden);
	}

	/**
	 * @param string $uri
	 * @return string|boolean
	 */
	public static function findSimilar($uri)
	{
		if (empty($uri)) {
			return FALSE;
		}

		$uriSlugs = array_merge([''], preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY));

		$slugs = DB::table('pages')
			->select('id', 'slug')
			->wherein('status_id', config('pages.similar.find_in_statuses', []));

		if (config('pages.check_date')) {
			$slugs->where('published_at', '<=', DB::raw('NOW()'));
		}

		$slugs = $slugs->get()->lists('slug', 'id');

		$newSlugs = [];
		foreach ($uriSlugs as $slug) {
			if (in_array($slug, $slugs)) {
				$newSlugs[] = $slug;
				continue;
			}

			$similarPages = Text::similarWord($slug, $slugs);

			if (!empty($similarPages)) {
				$pageId = key($similarPages);
				$page = static::findById($pageId);
				$newSlugs[] = $page->getSlug();
			}
		}

		if (!config('pages.similar.return_parent_page') AND (count($uriSlugs) != count($newSlugs))) {
			return FALSE;
		}

		$uri = implode('/', $newSlugs);

		$page = static::find($uri);
		return $page ? $uri : FALSE;
	}

	/**
	 * @return FrontendPage
	 */
	public static function getInitialPage()
	{
		return static::$_initialPage;
	}

	/**
	 * @param $slug
	 * @param FrontendPage $parentPage
	 * @return string
	 */
	final protected static function getCacheId($slug, FrontendPage $parentPage = NULL)
	{
		if (is_array($slug)) {
			$slug = implode('::', $slug);
		}

		return $slug . $parentPage;
	}

	/**
	 * @param boolean $includeHidden
	 * @return array
	 */
	public static function getStatuses($includeHidden = FALSE)
	{
		$statuses = [static::STATUS_PUBLISHED];

		if ($includeHidden) {
			$statuses[] = static::STATUS_HIDDEN;
		}

		return $statuses;
	}

	/**
	 * @var string
	 */
	protected $title = '';

	/**
	 * @var string
	 */
	protected $breadcrumb;

	/**
	 * @var string
	 */
	protected $slug = '';

	/**
	 * @var string
	 */
	protected $meta_title = '';

	/**
	 * @var string
	 */
	protected $meta_keywords = '';

	/**
	 * @var string
	 */
	protected $meta_description = '';

	/**
	 * @var string
	 */
	protected $uri = '';

	/**
	 * @var string
	 */
	protected $behavior;

	/**
	 * @var integer
	 */
	protected $level = NULL;

	/**
	 * @var integer
	 */
	protected $status;

	/**
	 * @var string
	 */
	protected $robots;

	/**
	 * @var string
	 */
	protected $created_at;

	/**
	 * @var string
	 */
	protected $updated_at;

	/**
	 * @var integer
	 */
	protected $created_by_id;

	/**
	 * @var integer
	 */
	protected $updated_by_id;

	/**
	 * @var null|integer
	 */
	protected $parent_id = NULL;

	/**
	 * @var integer
	 */
	protected $position = 0;

	/**
	 * @var bool
	 */
	protected $is_redirect = FALSE;

	/**
	 * @var string
	 */
	protected $redirect_url = NULL;

	/**
	 *
	 * @var string
	 */
	protected $layout_file = NULL;

	/**
	 * @var Behavior
	 */
	protected $_behaviorObject = NULL;

	/**
	 * @var FrontendPage
	 */
	protected $_parentPage = NULL;

	/**
	 * @var array
	 */
	protected $_metaParams = [];

	/**
	 * @param \stdClass $pageData
	 * @param FrontendPage $parentPage
	 */
	public function __construct(\stdClass $pageData, FrontendPage $parentPage = NULL)
	{
		if (!is_null($parentPage)) {
			$this->setParentPage($parentPage);
		}

		foreach ($pageData as $key => $value) {
			$this->$key = $value;
		}

		if ($this->getParent() instanceof FrontendPage) {
			$this->buildUri();
		}
	}

	/**
	 * @param FrontendPage $parentPage
	 * @return $this
	 */
	public function setParentPage(FrontendPage $parentPage = NULL)
	{
		if (!is_null($parentPage)) {
			$this->_parentPage = $parentPage;
			$this->buildUri();
		}

		return $this;
	}

	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->parseMeta('title');
	}

	/**
	 * @return string
	 */
	public function getMetaTitle()
	{
		return $this->parseMeta('meta_title');
	}

	/**
	 * @return Layout
	 */
	public function getLayoutFile()
	{
		if (empty($this->layout_file) AND $parent = $this->getParent()) {
			return $parent->getLayoutFile();
		}

		$layout = (new LayoutCollection)->findFile($this->layout_file);
		return $layout;
	}

	/**
	 * @return \Illuminate\View\View|null
	 */
	public function getLayoutView()
	{
		$layout = $this->getLayoutFile();

		if(!$layout) {
			return NULL;
		}
		return view('frontend::' . $layout->getViewFilename());
	}

	/**
	 * @param null $default
	 * @return null|string
	 */
	public function getMetaKeywords($default = NULL)
	{
		$meta = $this->parsMeta('meta_keywords');

		return !empty($meta)
			? $meta
			: $default;
	}

	/**
	 * @param null $default
	 * @return null|string
	 */
	public function getMetaDescription($default = NULL)
	{
		$meta = $this->parsMeta('meta_description');

		return !empty($meta)
			? $meta
			: $default;
	}

	/**
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * @return string
	 */
	public function getBreadcrumb()
	{
		if (empty($this->breadcrumb)) {
			$this->breadcrumb = $this->title;
		}

		return $this->parseMeta('breadcrumb');
	}

	/**
	 * @return int|null
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}

	/**
	 * @return Carbon
	 */
	public function getCreatedAt()
	{
		return (new Carbon)->createFromFormat('Y-m-d H:i:s', $this->created_at);
	}

	/**
	 * @return Carbon
	 */
	public function getUpdatedAt()
	{
		return (new Carbon)->createFromFormat('Y-m-d H:i:s', $this->updated_at);
	}

	/**
	 * @param null|intger $level
	 * @return FrontendPage|null
	 */
	public function getParent($level = NULL)
	{
		if ($this->_parentPage === NULL AND is_numeric($this->getParentId())) {
			return static::findById($this->getParentId());
		}

		if ($level === NULL) {
			return $this->_parentPage;
		}

		if ($level > $this->getLevel()) {
			return NULL;
		} else if ($this->getLevel() == $level) {
			return $this;
		} else if ($this->getParent() instanceof FrontendPage) {
			return $this->getParent()->getParent($level);
		}

		return NULL;
	}

	/**
	 *
	 * @param string|array $key
	 * @param string $value
	 *
	 * @return $this
	 */
	public function getMetaParam($key, $value = NULL, $field = NULL)
	{
		$this->_meta_params[$key] = $field === NULL
			? $value
			: $this->parse_meta($field, $value);

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUri()
	{
		return $this->uri;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return app('url')->to($this->getUri());
	}

	/**
	 * @return integer
	 */
	public function getLevel()
	{
		if ($this->level === NULL) {
			$uri = $this->getUri();
			$this->level = empty($uri) ? 0 : substr_count($uri, '/') + 1;
		}

		return $this->level;
	}

	/**
	 * @return null|Behavior
	 */
	public function getBehavior()
	{
		return $this->behavior;
	}

	/**
	 * @return string
	 */
	public function getMime()
	{
		$mime = File::mimeByExt(pathinfo($this->getUri(), PATHINFO_EXTENSION));

		return $mime === FALSE ? 'text/html' : $mime;
	}

	/**
	 * @param null|string $label
	 * @param array|null $attributes
	 * @param bool $checkCurrent
	 * @return string
	 */
	public function getAnchor($label = NULL, array $attributes = NULL, $checkCurrent = TRUE)
	{
		if ($label == NULL) {
			$label = $this->getTitle();
		}

		if ($checkCurrent === TRUE) {
			if ($this->isActive()) {
				if (!isset($attributes['class'])) {
					$attributes['class'] = '';
				}

				$attributes['class'] .= ' current';
			}
		}

		return \HTML::link($this->getUrl(), $label, $attributes);
	}

	/**
	 * @return array
	 */
	public function getMetaParams()
	{
		return $this->_metaParams;
	}

	/**
	 * @return bool
	 */
	public function isRedirect()
	{
		return (bool) $this->is_redirect;
	}


	/**
	 * @return string
	 */
	public function getRedirectUrl()
	{
		return $this->redirect_url;
	}

	/**
	 * @param string|array $key
	 * @param null|string $value
	 * @param null|string $field
	 * @return $this
	 */
	public function setMetaParams($key, $value = NULL, $field = NULL)
	{
		if (is_array($key)) {
			foreach ($key as $key2 => $value) {
				$this->_metaParams[$key2] = $value;
			}
		} else {
			$this->_metaParams[$key] = $field === NULL
				? $value
				: $this->parseMeta($field, $value);
		}

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		$url = $this->getUri();
		if (empty($url)) {
			return FALSE;
		}

		return (strpos(Request::path(), $url) === 1);
	}

	/**
	 * @return $this
	 */
	protected function buildUri()
	{
		$this->uri = trim($this->getParent()->getUri() . '/' . $this->getSlug(), '/');

		return $this;
	}

	/**
	 * @param $key
	 * @param null|string $value
	 * @return string
	 */
	public function parseMeta($key, $value = NULL)
	{
		if ($value === NULL) {
			$value = strtr($this->{$key}, ['\'' => '\\\'', '\\' => '\\\\']);
		}

		$fields = [];

		$found = preg_match_all(
			'/(?<!\{)\{(' .
			'((\$|\:)[A-Za-z0-9_\-\.\/]+(\|[\w\ ]*)?)' . // {$abc}, {:abc}
			'|[\.]+' .
			')\}(?!\})/u', $value, $fields);

		if ($found) {
			$fields = array_unique($fields[1]);
			$parts = [];

			foreach ($fields as $i => $field) {
				$patterns[] = '/(?<!\\{)\\{' . preg_quote($field, '/') . '\\}(?!\\})/u';
				switch ($field) {
					case '.': // Current page
						if ($key == 'meta_title') {
							$parts[] = $this->getTitle();
						}
						break;
					case '..': // Parent page
						if ($this->getParent() instanceof FrontendPage) {
							$method = 'get' . ucfirst($key);
							$parts[] = $this->getParent()->{$method}();
						}
						break;
					default: // Level
						if (
							is_numeric($field)
							AND
							$this->getLevel() != $field
							AND
							$this->getParent($field) instanceof FrontendPage
						) {
							$method = 'get' . ucfirst($key);
							$parts[] = $this->getParent($field)->{$method}();
						}
						break;
				}

				$param = NULL;
				$metaParam = NULL;
				$default = NULL;

				if (strpos($field, '|') !== FALSE) {
					list($field, $default) = explode('|', $field, 2);
				}

				switch ($field{0}) {
					case '$':
						$param = substr($field, 1);
						break;
					case ':':
						$metaParam = substr($field, 1);
						break;
				}

				if ($param !== NULL) {
					if (strpos($param, 'site.') !== FALSE) {
						$parts[] = config('app.' . substr($param, 5), $default);
					} else if (strpos($param, 'ctx.') !== FALSE) {
						$parts[] = app('ctx')->get(substr($param, 4));
					} else if (
						strpos($param, 'parent.') !== FALSE
						AND
						$this->getParent() instanceof FrontendPage
						AND
						method_exists($this, ($method = 'get' . ucfirst(substr($param, 7))))
					) {
						$parts[] = $this->getParent()->{$method}();
					} else if (method_exists($this, ($method = 'get' . ucfirst($param)))) {
						$parts[] = $this->{$method}();
					}
				}

				if ($metaParam !== NULL) {
					$parts[] = array_get($this->_metaParams, $metaParam, $default);
				}
			}

			$value = preg_replace($patterns, $parts, $value);
		}

		return $value;
	}

	/**
	 *
	 * @return string
	 */
	public function __toString()
	{
		return (string)$this->getId();
	}
}
