<?php namespace KodiCMS\Widgets\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Widgets\Exceptions\WidgetException;
use KodiCMS\Widgets\Manager as WidgetsManager;

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
	 * @return string
	 */
	public function getType()
	{
		foreach (WidgetsManager::getAvailableWidgets() as $group => $types)
		{
			if (isset($types[$this->type]))
			{
				return $types[$this->type];
			}
		}

		return $this->type;
	}

	public function asWidget()
	{
		if(!$this->exists)
		{
			return null;
		}

		$widgetClass = $this->class;

		if(!in_array('KodiCMS\Widgets\Contracts\Widget', class_implements($widgetClass)))
		{
			throw new WidgetException("Widget class {$widgetClass} must be implemented of [KodiCMS\Widgets\Contracts\Widget]");
		}

		$widget = new $widgetClass($this->id, $this->type, $this->name, $this->description);

		$widget->setParameters($this->parameters);
		$widget->setSettings($this->settings);

		return $widget;
	}

	public function scopeFilterByType($query, array $types)
	{
		if(count($types) > 0)
		{
			return $query->whereIn('type', $types);
		}
	}
}