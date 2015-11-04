<?php

namespace KodiCMS\Widgets\Model;

use DB;
use KodiCMS\Pages\Model\Page;
use Illuminate\Database\Eloquent\Model;
use KodiCMS\Widgets\Widget\Temp as TempWidget;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class Widget extends Model
{
    /**
     * @var array
     */
    private static $cachedWidgets = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'type',
        'template',
        'settings',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'name'        => 'string',
        'description' => 'string',
        'type'        => 'string',
        'template'    => 'string',
        'settings'    => 'array',
    ];

    /**
     * @var \KodiCMS\Widgets\Contracts\Widget
     */
    protected $widget = null;

    /**
     * @param string $template
     */
    public function setTemplateAttribute($template)
    {
        $this->attributes['template'] = empty($template) ? null : $template;
    }

    /**
     * @return string
     */
    public function getType()
    {
        foreach (WidgetManagerDatabase::getAvailableTypes() as $group => $types) {
            if (isset($types[$this->type])) {
                return $types[$this->type];
            }
        }

        return $this->type;
    }

    /**
     * @return \KodiCMS\Widgets\Contracts\Widget|null
     * @throws WidgetException
     */
    public function toWidget()
    {
        if (! is_null($this->widget)) {
            return $this->widget;
        }

        if (array_key_exists($this->id, static::$cachedWidgets)) {
            $this->widget = static::$cachedWidgets[$this->id];

            return $this->widget;
        }

        if (! is_null($this->widget = WidgetManagerDatabase::makeWidget($this->type, $this->name, $this->description, $this->settings))) {
            $this->widget->setId($this->id);

            if ($this->isRenderable()) {
                $this->widget->setFrontendTemplate($this->template);
            }

            $this->widget->setRalatedWidgets($this->related);
        } else {
            $this->widget = new TempWidget($this->name, $this->description);
        }

        static::$cachedWidgets[$this->id] = $this->widget;

        return $this->widget;
    }

    /**
     * @return bool
     */
    public function isWidgetable()
    {
        return ($this->exists and WidgetManagerDatabase::isWidgetable(get_class($this->toWidget())));
    }

    /**
     * @return bool
     */
    public function isHandler()
    {
        return WidgetManagerDatabase::isHandler(get_class($this->toWidget()));
    }

    /**
     * @return bool
     */
    public function isRenderable()
    {
        return WidgetManagerDatabase::isRenderable(get_class($this->toWidget()));
    }

    /**
     * @return bool
     */
    public function isCacheable()
    {
        return WidgetManagerDatabase::isCacheable(get_class($this->toWidget()));
    }

    /**
     * @return bool
     */
    public function isClassExists()
    {
        return WidgetManagerDatabase::isClassExists(get_class($this->toWidget()));
    }

    /**
     * @return bool
     */
    public function isCorrupt()
    {
        return WidgetManagerDatabase::isCorrupt(get_class($this->toWidget()));
    }

    public function scopeFilterByType($query, array $types)
    {
        if (count($types) > 0) {
            return $query->whereIn('type', $types);
        }
    }

    /**
     * @return array
     */
    public function getLocations()
    {
        if (! $this->exists) {
            return [null, null];
        }

        $query = DB::table('page_widgets')->get();

        $blocksToExclude = []; // занятые блоки для исключения из списков
        $widgetBlocks = []; // выбранные блоки для текущего виджета

        foreach ($query as $row) {
            if ($row->widget_id == $this->id) {
                $widgetBlocks[$row->page_id] = [$row->block, $row->position];
            } else {
                $blocksToExclude[$row->page_id][$row->block] = [$row->block, $row->position];
            }
        }

        return [$widgetBlocks, $blocksToExclude];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pages()
    {
        return $this->belongsToMany(Page::class, 'page_widgets', 'widget_id', 'page_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasManyThrough
     */
    public function related()
    {
        return $this->belongsToMany(self::class, 'related_widgets', 'to_widget_id', 'id');
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (method_exists($this->toWidget(), $method)) {
            return call_user_func_array([$this->toWidget(), $method], $parameters);
        }

        return parent::__call($method, $parameters);
    }
}
