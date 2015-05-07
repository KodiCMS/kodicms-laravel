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
		'name', 'description', 'type', 'class', 'template', 'parameters', 'settings'
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
		'class' => 'string',
		'template' => 'string',
		'parameters' => 'array',
		'settings' => 'array',
	];

	/**
	 * @var \KodiCMS\Widgets\Contracts\Widget
	 */
	protected $widget = null;

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
		if (!$this->exists or !$this->isClassExists())
		{
			return null;
		}

		if (!is_null($this->widget))
		{
			return $this->widget;
		}

		$widgetClass = $this->class;

		if ($this->isCorrupt())
		{
			throw new WidgetException("Widget class {$widgetClass} must be implemented of [KodiCMS\Widgets\Contracts\Widget]");
		}

		$this->widget = new $widgetClass($this->id, $this->type, $this->name, $this->description);

		if (!is_null($this->parameters))
		{
			$this->widget->setParameters($this->parameters);
		}

		if (!is_null($this->settings))
		{
			$this->widget->setSettings($this->settings);
		}

		return $this->widget;
	}

	/**
	 * @return bool
	 */
	public function isHandler()
	{
		return in_array('KodiCMS\Widgets\Contracts\WidgetHandler', class_implements($this->class));
	}

	/**
	 * @return bool
	 */
	public function isRenderable()
	{
		return in_array('KodiCMS\Widgets\Contracts\WidgetRenderable', class_implements($this->class));
	}

	/**
	 * @return bool
	 */
	public function isCacheable()
	{
		return in_array('KodiCMS\Widgets\Contracts\WidgetCacheable', class_implements($this->class));
	}

	/**
	 * @return bool
	 */
	public function isClassExists()
	{
		return class_exists($this->class);
	}

	/**
	 * @return bool
	 */
	public function isCorrupt()
	{
		return !in_array('KodiCMS\Widgets\Contracts\Widget', class_implements($this->class));
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
		if ($this->exists and !$this->isCorrupt() and $this->isClassExists() and method_exists($this->toWidget(), $method))
		{
			return call_user_func_array([$this->toWidget(), $method], $parameters);
		}

		return parent::__call($method, $parameters);
	}
}