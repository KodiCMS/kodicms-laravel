<?php namespace KodiCMS\Widgets\Model;

use Illuminate\Database\Eloquent\Model;
use KodiCMS\Widgets\Manager as WidgetsManager;

class Widget extends Model
{
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

	public function toWidget()
	{
		if(!$this->exists)
		{
			return null;
		}


	}

	public function scopeFilterByType($query, array $types)
	{
		if(count($types) > 0)
		{
			return $query->whereIn('type', $types);
		}
	}
}