<?php namespace KodiCMS\Pages\Model;

use DB;
use Cache;
use Request;
use Carbon\Carbon;
use KodiCMS\Users\Model\User;
use KodiCMS\Support\Helpers\File;
use KodiCMS\Support\Helpers\Text;
use Illuminate\Database\Query\Builder;
use KodiCMS\Pages\Contracts\BehaviorPageInterface;
use KodiCMS\CMS\Breadcrumbs\Collection as Breadcrumbs;
use KodiCMS\Pages\Behavior\Manager as BehaviorManager;

class FrontendPage implements BehaviorPageInterface
{
	const STATUS_DRAFT     = 1;
	const STATUS_PUBLISHED = 100;
	const STATUS_HIDDEN    = 101;

	/**
	 * @var array
	 */
	private static $pagesCache = [];

	/**
	 * @param $field
	 * @param $value
	 * @param FrontendPage $parentPage
	 * @param boolean|array $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findByField($field, $value, FrontendPage $parentPage = null, $includeHidden = true)
	{
		$pageCacheId = static::getCacheId([$field, $value, $includeHidden ? 'TRUE' : 'FALSE'], $parentPage);

		if (isset(static::$pagesCache[$pageCacheId]))
		{
			return static::$pagesCache[$pageCacheId];
		}

		$query = DB::table('pages')->where('pages.' . $field, $value)->whereIn('status', static::getStatuses($includeHidden));

		if (config('pages::checkDate') === true)
		{
			$query->where('published_at', '<=', DB::raw('NOW()'));
		}

		if (!is_null($parentPage))
		{
			$query->where('parent_id', $parentPage->id);
		}

		// TODO: добавить кеширование на основе тегов
		$foundPage = Cache::remember($pageCacheId, Carbon::now()->addMinutes(10), function() use($query)
		{
			return $query->take(1)->first();
		});


		if (is_null($foundPage))
		{
			return null;
		}

		$foundPageObject = new FrontendPage($foundPage);

		if (is_null($parentPage) AND !is_null($foundPageObject->getParentId()))
		{
			$parentPage = static::findById($foundPageObject->getParentId());
		}

		$foundPageObject->setParentPage($parentPage);

		static::$pagesCache[$pageCacheId] = $foundPageObject;

		return $foundPageObject;
	}

	/**
	 * @param string $uri
	 * @param boolean|array $includeHidden
	 * @param FrontendPage $parentPage
	 * @return stdClass
	 */
	public static function findByUri($uri, FrontendPage $parentPage = null, $includeHidden = true)
	{
		$uri = trim($uri, '/');

		$urls = preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY);

		if (is_null($parentPage))
		{
			$urls = array_merge([''], $urls);
		}

		$url = '';

		$pageObject = new \stdClass;
		$pageObject->id = 0;

		foreach ($urls as $pageSlug)
		{
			$url = ltrim($url . '/' . $pageSlug, '/');

			if ($pageObject = static::findBySlug($pageSlug, $parentPage, $includeHidden))
			{
				if ($pageObject->hasBehavior() AND !is_null($behavior = BehaviorManager::load($pageObject->getBehavior())))
				{
					$behavior->setPage($pageObject);
					$behavior->executeRoute(substr($uri, strlen($url)));

					$pageObject->behaviorObject = $behavior;
					return $pageObject;
				}
			}
			else
			{
				break;
			}

			$parentPage = $pageObject;
		}

		return $pageObject;
	}

	/**
	 * @param $slug
	 * @param FrontendPage $parentPage
	 * @param boolean|array $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findBySlug($slug, FrontendPage $parentPage = null, $includeHidden = true)
	{
		return self::findByField('slug', $slug, $parentPage, $includeHidden);
	}

	/**
	 * @param $id
	 * @param boolean|array $includeHidden
	 * @return bool|FrontendPage
	 */
	public static function findById($id, $includeHidden = true)
	{
		return self::findByField('id', (int)$id, null, $includeHidden);
	}

	/**
	 * @param string $uri
	 * @return string|boolean
	 */
	public static function findSimilar($uri)
	{
		if (empty($uri))
		{
			return false;
		}

		$uriSlugs = array_merge([''], preg_split('/\//', $uri, -1, PREG_SPLIT_NO_EMPTY));

		$slugs = DB::table('pages')->select('id', 'slug')->wherein('status_id', config('pages.similar.find_in_statuses', []));

		if (config('pages.check_date'))
		{
			$slugs->where('published_at', '<=', DB::raw('NOW()'));
		}

		$slugs = $slugs->get()->lists('slug', 'id')->all();

		$newSlugs = [];
		foreach ($uriSlugs as $slug)
		{
			if (in_array($slug, $slugs))
			{
				$newSlugs[] = $slug;
				continue;
			}

			$similarPages = Text::similarWord($slug, $slugs);

			if (!empty($similarPages))
			{
				$pageId = key($similarPages);
				$page = static::findById($pageId);
				$newSlugs[] = $page->getSlug();
			}
		}

		if (!config('pages.similar.return_parent_page') AND (count($uriSlugs) != count($newSlugs)))
		{
			return false;
		}

		$uri = implode('/', $newSlugs);

		$page = static::find($uri);

		return $page ? $uri : false;
	}

	/**
	 * @param $slug
	 * @param FrontendPage $parentPage
	 * @return string
	 */
	final protected static function getCacheId($slug, FrontendPage $parentPage = null)
	{
		if (is_array($slug))
		{
			$slug = implode('::', $slug);
		}

		return $slug . $parentPage;
	}

	/**
	 * @param boolean|array $includeHidden
	 * @return array
	 */
	public static function getStatuses($includeHidden = false)
	{
		$statuses = [static::STATUS_PUBLISHED];

		if ($includeHidden === true)
		{
			$statuses[] = static::STATUS_HIDDEN;
		}
		else if (is_array($includeHidden))
		{
			$statuses = array_merge($statuses, $includeHidden);
		}

		return $statuses;
	}

	/**
	 * @var int
	 */
	protected $id;

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
	protected $level = null;

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
	 * @var string
	 */
	protected $published_at;

	/**
	 * @var integer
	 */
	protected $created_by_id;

	/**
	 * @var integer
	 */
	protected $updated_by_id;

	/**
	 * @var User
	 */
	protected $created_by = null;

	/**
	 * @var User
	 */
	protected $updated_by = null;

	/**
	 * @var null|integer
	 */
	protected $parent_id = null;

	/**
	 * @var integer
	 */
	protected $position = 0;

	/**
	 * @var bool
	 */
	protected $is_redirect = false;

	/**
	 * @var string
	 */
	protected $redirect_url = null;

	/**
	 *
	 * @var string
	 */
	protected $layout_file = null;

	/**
	 * @var Behavior
	 */
	protected $behaviorObject = null;

	/**
	 * @var FrontendPage
	 */
	protected $parentPage = null;

	/**
	 * @var array
	 */
	protected $metaParams = [];

	/**
	 * @param \stdClass $pageData
	 * @param FrontendPage $parentPage
	 */
	public function __construct($pageData, FrontendPage $parentPage = null)
	{
		if (!is_null($parentPage))
		{
			$this->setParentPage($parentPage);
		}

		foreach ($pageData as $key => $value)
		{
			$this->$key = $value;
		}

		if ($this->getParent() instanceof FrontendPage)
		{
			$this->buildUri();
		}
	}

	/**
	 * @param FrontendPage $parentPage
	 * @return $this
	 */
	public function setParentPage(FrontendPage $parentPage = null)
	{
		if (!is_null($parentPage))
		{
			$this->parentPage = $parentPage;
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
	public function getMetaRobots()
	{
		return $this->robots;
	}

	/**
	 * @return string
	 */
	public function getMetaTitle()
	{
		return $this->parseMeta('meta_title');
	}

	public function getLayout()
	{
		if (empty($this->layout_file) AND $parent = $this->getParent())
		{
			return $parent->getLayout();
		}

		return $this->layout_file;
	}

	/**
	 * @return Layout
	 */
	public function getLayoutFile()
	{
		return (new LayoutCollection)->findFile($this->getLayout());
	}

	/**
	 * @return \Illuminate\View\View|null
	 */
	public function getLayoutView()
	{
		$layout = $this->getLayoutFile();

		if (!$layout)
		{
			return null;
		}

		return $layout->toView();
	}

	/**
	 * @param null $default
	 * @return null|string
	 */
	public function getMetaKeywords($default = null)
	{
		$meta = $this->parseMeta('meta_keywords');

		return !empty($meta) ? $meta : $default;
	}

	/**
	 * @param null $default
	 * @return null|string
	 */
	public function getMetaDescription($default = null)
	{
		$meta = $this->parseMeta('meta_description');

		return !empty($meta) ? $meta : $default;
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
		if (empty($this->breadcrumb))
		{
			$this->breadcrumb = $this->title;
		}

		return $this->parseMeta('breadcrumb');
	}

	/**
	 * @param int $level
	 * @return Breadcrumbs
	 */
	public function getBreadcrumbs($level = 0)
	{
		$crumbs = Breadcrumbs::factory();

		if (($parent = $this->getParent()) instanceof FrontendPage AND $this->getLevel() > $level)
		{
			$this->getParent()->recurseBreadcrumbs($level, $crumbs);
		}

		$crumbs->add($this->getBreadcrumb(), $this->getUrl(), true, null, ['id' => $this->getId()]);

		return $crumbs;
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
	 * @return Carbon
	 */
	public function getPublishedAt()
	{
		return (new Carbon)->createFromFormat('Y-m-d H:i:s', $this->published_at);
	}

	/**
	 * @return User
	 */
	public function getCreatedBy()
	{
		if(is_null($this->created_by))
		{
			$this->created_by = User::findOrNew($this->created_by);
		}

		return $this->created_by;
	}

	/**
	 * @return User
	 */
	public function getUpdatedBy()
	{
		if(is_null($this->updated_by))
		{
			$this->updated_by = User::findOrNew($this->created_by);
		}

		return $this->updated_by;
	}

	/**
	 * @param null|intger $level
	 * @return FrontendPage|null
	 */
	public function getParent($level = null)
	{
		if ($this->parentPage === null AND is_numeric($this->getParentId()) AND $this->getParentId() > 0)
		{
			return static::findById($this->getParentId());
		}

		if ($level === null)
		{
			return $this->parentPage;
		}

		if ($level > $this->getLevel())
		{
			return null;
		}
		else if ($this->getLevel() == $level)
		{
			return $this;
		}
		else if ($this->getParent() instanceof FrontendPage)
		{
			return $this->getParent()->getParent($level);
		}

		return null;
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
		if ($this->level === null)
		{
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
	 * @return bool
	 */
	public function hasBehavior()
	{
		return ! empty($this->behavior);
	}

	/**
	 * @return string
	 */
	public function getMime()
	{
		$mime = File::mimeByExt(pathinfo($this->getUri(), PATHINFO_EXTENSION));

		return $mime === false ? 'text/html' : $mime;
	}

	/**
	 * @param null|string $label
	 * @param array|null $attributes
	 * @param bool $checkCurrent
	 * @return string
	 */
	public function getAnchor($label = null, array $attributes = null, $checkCurrent = true)
	{
		if ($label == null)
		{
			$label = $this->getTitle();
		}

		if ($checkCurrent === true)
		{
			if ($this->isActive())
			{
				if (!isset($attributes['class']))
				{
					$attributes['class'] = '';
				}

				$attributes['class'] .= ' current';
			}
		}

		return \HTML::link($this->getUrl(), $label, $attributes);
	}

	/**
	 * @return bool
	 */
	public function isRedirect()
	{
		return (bool)$this->is_redirect;
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
	public function setMetaParams($key, $value = null, $field = null)
	{
		if (is_array($key))
		{
			foreach ($key as $key2 => $value)
			{
				$this->metaParams[$key2] = $value;
			}
		}
		else
		{
			$this->metaParams[$key] = is_null($field) ? $value : $this->parseMeta($field, $value);
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getMetaParams()
	{
		return $this->metaParams;
	}

	/**
	 * @param string $key
	 * @param mixed $default
	 *
	 * @return $this
	 */
	public function getMetaParam($key, $default = null)
	{
		return array_get($this->metaParams, $key, $default);
	}

	/**
	 * @return boolean
	 */
	public function isActive()
	{
		$url = $this->getUri();
		if (empty($url))
		{
			return false;
		}

		return (strpos(Request::path(), $url) === 1);
	}

	/**
	 * @param $key
	 * @param null|string $value
	 * @return string
	 */
	public function parseMeta($key, $value = null)
	{
		if ($value === null)
		{
			$value = strtr($this->{$key}, ['\'' => '\\\'', '\\' => '\\\\']);
		}

		$fields = [];

		$found = preg_match_all('/(?<!\{)\{(' . '((\$|\:)[A-Za-z0-9_\-\.\/]+(\|[\w\ ]*)?)' . // {$abc}, {:abc}
			'|[\.]+' . ')\}(?!\})/u', $value, $fields);

		if ($found)
		{
			$fields = array_unique($fields[1]);
			$parts = [];

			foreach ($fields as $i => $field)
			{
				$patterns[] = '/(?<!\\{)\\{' . preg_quote($field, '/') . '\\}(?!\\})/u';
				switch ($field)
				{
					case '.': // Current page
						if ($key == 'meta_title')
						{
							$parts[] = $this->getTitle();
						}
						break;
					case '..': // Parent page
						if ($this->getParent() instanceof FrontendPage)
						{
							$method = 'get' . ucfirst($key);
							$parts[] = $this->getParent()->{$method}();
						}
						break;
					default: // Level
						if (is_numeric($field) AND $this->getLevel() != $field AND $this->getParent($field) instanceof FrontendPage
						)
						{
							$method = 'get' . ucfirst($key);
							$parts[] = $this->getParent($field)->{$method}();
						}
						break;
				}

				$param = null;
				$metaParam = null;
				$default = null;

				if (strpos($field, '|') !== false)
				{
					list($field, $default) = explode('|', $field, 2);
				}

				switch ($field{0})
				{
					case '$':
						$param = substr($field, 1);
						break;
					case ':':
						$metaParam = substr($field, 1);
						break;
				}

				if ($param !== null)
				{
					if (strpos($param, 'cms.') !== false)
					{
						$parts[] = config('cms.' . substr($param, 5), $default);
					}
					else if (strpos($param, 'parent.') !== false AND $this->getParent() instanceof FrontendPage AND method_exists($this, ($method = 'get' . ucfirst(substr($param, 7))))
					)
					{
						$parts[] = $this->getParent()->{$method}();
					}
					else if (method_exists($this, ($method = 'get' . ucfirst($param))))
					{
						$parts[] = $this->{$method}();
					}
				}

				if ($metaParam !== null)
				{
					$parts[] = array_get($this->metaParams, $metaParam, $default);
				}
			}

			$value = preg_replace($patterns, $parts, $value);
		}

		return $value;
	}

	/**
	 * @param boolean $includeHidden
	 * @return integer
	 */
	public function childrenCount($includeHidden = false)
	{
		$query = Page::where('parent_id', $this->getId())
			->whereIn('status', static::getStatuses($includeHidden))
			->orderBy('position', 'desc');

		if (filter_var(config('pages.check_date'), FILTER_VALIDATE_BOOLEAN))
		{
			$query->whereRaw('published_at <= NOW()');
		}

		return $query->count();;
	}

	/**
	 * @param boolean $includeHidden
	 * @return array
	 */
	public function getChildren($includeHidden = false)
	{
		$pages = [];
		foreach ($this->getChildrenQuery($includeHidden)->get() as $row)
		{
			$pages[$row->id] = new static($row->toArray(), $this);
		}

		return $pages;
	}

	/**
	 * @return Builder
	 */
	public function getChildrenQuery($includeHidden = false)
	{
		$query = Page::where('parent_id', $this->getId())
			->whereIn('status', static::getStatuses($includeHidden))
			->orderBy('position', 'desc');

		if (filter_var(config('pages.check_date'), FILTER_VALIDATE_BOOLEAN))
		{
			$query->whereRaw('published_at <= NOW()');
		}

		return $query;
	}

	/**
	 * @return array
	 */
	public function getBehaviorSettings()
	{
		$settings = PageBehaviorSettings::where('page_id', $this->getId())->first();
		return is_null($settings) ? [] : $settings->settings;
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
	 * @param integer $level
	 * @param Breadcrumbs $crumbs
	 */
	private function recurseBreadcrumbs($level, Breadcrumbs & $crumbs)
	{
		if (($parent = $this->getParent()) instanceof FrontendPage AND $this->getLevel() > $level)
		{
			$parent->recurseBreadcrumbs($level, $crumbs);
		}

		$crumbs->add($this->getBreadcrumb(), $this->getUrl(), false, null, ['id' => $this->getId()]);
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
