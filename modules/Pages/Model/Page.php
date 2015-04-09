<?php namespace KodiCMS\Pages\Model;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
	/**
	 * Список статусов
	 * @return array
	 */
	public static function getStatusList()
	{
		return [
			FrontendPage::STATUS_DRAFT => trans('pages::core.status.draft'),
			FrontendPage::STATUS_PUBLISHED => trans('pages::core.status.published'),
			FrontendPage::STATUS_HIDDEN => trans('pages::core.status.hidden')
		];
	}

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'pages';

	/**
	 * The attributes that aren't mass assignable.
	 *
	 * @var array
	 */
	protected $guarded = ['id', 'created_at', 'updated_at', 'created_by_id', 'updated_by_id'];

	/**
	 * The attributes that should be mutated to dates.
	 *
	 * @var array
	 */
	protected $dates = ['published_at'];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'parent_id' => 'integer',
		'status' => 'integer',
		'created_by_id' => 'integer',
		'updated_by_id' => 'integer',
		'position' => 'integer',
		'is_redirect' => 'boolean',
	];

	/**
	 * @var array
	 */
	protected $metaFields = [
		'breadcrumb', 'meta_title', 'meta_keywords', 'meta_description'
	];

	/**
	 * @var boolean
	 */
	public $isExpanded = FALSE;

	/**
	 * @var boolean
	 */
	public $hasChildren = FALSE;

	/**
	 * @var array
	 */
	public $childrenRows = NULL;

	/**
	 * @return array
	 */
	public function getMetaFields()
	{
		return $this->metaFields;
	}

	public function getRobotsList()
	{
		return [
			'INDEX, FOLLOW' => 'INDEX, FOLLOW',
			'INDEX, NOFOLLOW' => 'INDEX, NOFOLLOW',
			'NOINDEX, FOLLOW' => 'NOINDEX, FOLLOW',
			'NOINDEX, NOFOLLOW' => 'NOINDEX, NOFOLLOW'
		];
	}

	/**
	 * @param array $attributes
	 */
	public function __construct(array $attributes = [])
	{
		parent::__construct($attributes);
		$this->addObservableEvents(['reordering', 'reordered']);
	}

	/**
	 * Статус страницы
	 * @return string
	 */
	public function getStatus()
	{
		$status = trans('pages::core.status.none');
		$label = 'default';

		switch ($this->status) {
			case FrontendPage::STATUS_DRAFT:
				$status = trans('pages::core.status.draft');
				$label = 'info';
				break;
			case FrontendPage::STATUS_HIDDEN:
				$status = trans('pages::core.status.hidden');
				break;
			case FrontendPage::STATUS_PUBLISHED:
				if (strtotime($this->published_at) > time()) {
					$status = trans('pages::core.status.pending');
				} else {
					$status = trans('pages::core.status.published');
				}

				$label = 'success';
				break;
		}

		return \UI::label($status, $label . ' editable-status', ['data-value' => $this->status]);
	}

	/**
	 * @return string
	 */
	public function getUri()
	{
		if ($parent = $this->parent()->first()) {
			$uri = $parent->getUri() . '/' . $this->slug;
		} else {
			$uri = $this->slug;
		}

		return $uri;
	}

	/**
	 * @return string
	 */
	public function getFrontendUrl()
	{
		return url($this->getUri());
	}

	/**
	 * Получение ссылки на страницу
	 * @return string
	 */
	public function getPublicLink()
	{
		return link_to($this->getFrontendUrl(), \UI::label(\UI::icon('globe') . ' ' . trans('pages::core.button.view_front')), [
			'class' => 'item-preview', 'target' => '_blank'
		]);
	}

	/**
	 * Получение названия шаблона текущей страницы
	 * @return string
	 */
	public function getLayout()
	{
		if (empty($this->layout_file) AND $parent = $this->parent()->first()) {
			return $parent->getLayout();
		}

		return $this->layout_file;
	}

	/**
	 * Проверка на существование внутренних страницы
	 * @return boolean
	 */
	public function hasChildren()
	{
		return (bool)\DB::table($this->table)
			->selectRaw('COUNT(*) as total')
			->where('parent_id', $this->id)
			->pluck('total') > 0;
	}

	/**
	 * @param string $keyword
	 * @return ORM
	 */
	public function scopeSearchByKeyword($query, $keyword)
	{
		return $query->where(function ($subQuery) use ($keyword) {
			$keyword = e($keyword);

			return $subQuery
				->orWhere(\DB::raw('LOWER(title)'), 'like', "%{$keyword}%")
				->orWhere('slug', 'like', "%{$keyword}%")
				->orWhere('breadcrumb', 'like', "%{$keyword}%")
				->orWhere('meta_title', 'like', "%{$keyword}%")
				->orWhere('meta_keywords', 'like', "%{$keyword}%");
		});
	}

	/**
	 * Получение списка страниц за исключением текущей
	 * @return array
	 */
	public function getSitemap()
	{
		$sitemap = PageSitemap::get(TRUE);
		if ($this->exists) {
			$sitemap->exclude([$this->id]);
		}

		return $sitemap->selectChoices();
	}

	public function createdBy()
	{
		return $this->belongsTo('\KodiCMS\Users\Model\User', 'created_by_id');
	}

	public function updatedBy()
	{
		return $this->belongsTo('\KodiCMS\Users\Model\User', 'updated_by_id');
	}

	public function parent()
	{
		return $this->belongsTo('\KodiCMS\Pages\Model\Page', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('\KodiCMS\Pages\Model\Page', 'parent_id', 'id');
	}

	/**
	 * @param array $pages
	 * @return bool
	 */
	public function reorder(array $pages)
	{
		$pages = array_map(function ($page) {
			$page['parent_id'] = empty($page['parent_id']) ? 1 : $page['parent_id'];
			$page['id'] = (int)$page['id'];
			$page['position'] = (int)$page['position'];

			return $page;
		}, $pages);

		if ($this->fireModelEvent('reordering') === FALSE) return FALSE;

		$builder = \DB::table('pages');
		$grammar = $builder->getGrammar();
		$insert = $grammar->compileInsert($builder, $pages);

		$bindings = [];

		foreach ($pages as $record) {
			foreach ($record as $value) {
				$bindings[] = $value;
			}
		}

		$insert .= ' ON DUPLICATE KEY UPDATE parent_id = VALUES(parent_id), position = VALUES(position)';

		\DB::insert($insert, $bindings);

		$this->fireModelEvent('reordered', FALSE);

		return TRUE;
	}

	/**
	 * @return array
	 */
	public function getLayoutList()
	{
		$options = [];

		if ($this->id != 1) {
			$layout = NULL;
			if ($parent = $this->parent()->first()) {
				$layout = $parent->getLayout();
			}

			if (empty($layout)) {
				$layout = trans('pages::layout.label.not_set');
			}

			$options[0] = trans('pages::layout.label.inherit', ['layout' => $layout]);
		} else {
			$options[0] = trans('pages::layout.label.not_set');
		}

		foreach ((new LayoutCollection())->getChoices() as $layout) {
			$options[$layout] = $layout;
		}

		return $options;
	}
}
