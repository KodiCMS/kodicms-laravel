<?php namespace KodiCMS\Widgets\Storage;

use KodiCMS\CMS\Exceptions\ValidationException;
use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetStorage;
use KodiCMS\Widgets\Model\Widget as WidgetModel;
use KodiCMS\Widgets\Services\WidgetCreator;
use KodiCMS\Widgets\Services\WidgetUpdator;

class WidgetSorageDatabase implements WidgetStorage
{
	/**
	 * @return bool|Widget
	 * @throws ValidationException
	 * @throws \KodiCMS\Widgets\Exceptions\WidgetException
	 */
	public function create(Widget $widget)
	{
		if ($widget->isExists())
		{
			return false;
		}

		$data = [
			'name' => $widget->getName(),
			'description' => $widget->getDescription(),
			'settings' => $widget->getSettings(),
			'type' => $widget->getType()
		];

		$creator = new WidgetCreator;

		$validator = $creator->validator($data);

		if ($validator->fails())
		{
			throw (new ValidationException)->setValidator($validator);
		}

		$widgetModel = $creator->create($data);

		$widget->setId($widgetModel->id);

		return $widget;
	}

	/**
	 * @param Widget $widget
	 * @return bool
	 * @throws ValidationException
	 */
	public function update(Widget $widget)
	{
		if (!$widget->isExists())
		{
			return false;
		}

		$data = [
			'name' => $widget->getName(),
			'description' => $widget->getDescription(),
			'settings' => $widget->getSettings()
		];

		$updator = new WidgetUpdator;
		$validator = $updator->validator($data);

		if ($validator->fails())
		{
			throw (new ValidationException)->setValidator($validator);
		}

		$updator->update($widget->getId(), $data);

		return true;
	}

	/**
	 * @param Widget $widget
	 * @return bool
	 */
	public function delete(Widget $widget)
	{
		if (!$this->widget->isExists())
		{
			return false;
		}

		WidgetModel::findOrFail($this->widget->getId())->delete();

		return true;
	}
}