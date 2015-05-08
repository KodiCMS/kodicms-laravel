<?php namespace KodiCMS\Widgets\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;

class Widget extends Model
{
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'description', 'type', 'class', 'template', 'settings'
	];

	/**
	 * The attributes that should be casted to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'name' => 'string',
		'description' => 'string',
		'type' => 'string',
		'clas' => 'string',
		'template' => 'string',
		'settings' => 'array',
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
		foreach (WidgetManagerDatabase::getAvailableTypes() as $group => $types)
		{
			if (isset($types[$this->type]))
			{
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
		if (!is_null($this->widget))
		{
			return $this->widget;
		}

		if (!is_null($this->widget = WidgetManagerDatabase::makeWidget($this->type, $this->name, $this->description, $this->settings)))
		{
			$this->widget->setId($this->id);
		}
		else
		{
			// TODO: возможно стоит переделать
			$this->widget = new \KodiCMS\Widgets\Widget\Temp($this->name, $this->description);
		}

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
		if(count($types) > 0)
		{
			return $query->whereIn('type', $types);
		}
	}

	/**
	 * Handle dynamic method calls into the model.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		if (method_exists($this->toWidget(), $method))
		{
			return call_user_func_array([$this->toWidget(), $method], $parameters);
		}

		return parent::__call($method, $parameters);
	}
}