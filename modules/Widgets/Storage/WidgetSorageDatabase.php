<?php namespace KodiCMS\Widgets\Storage;

use KodiCMS\CMS\Exceptions\ValidationException;
use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Contracts\WidgetDatabase;
use KodiCMS\Widgets\Contracts\WidgetStorage;
use KodiCMS\Widgets\Model\Widget as WidgetModel;
use KodiCMS\Widgets\Services\WidgetCreator;
use KodiCMS\Widgets\Services\WidgetUpdator;

class WidgetSorageDatabase implements WidgetStorage
{
	/**
	 * @return bool|WidgetDatabase
	 * @throws ValidationException
	 * @throws \KodiCMS\Widgets\Exceptions\WidgetException
	 */
	public function create(Widget $widget)
	{
		return $this->_create($widget);
	}

	/**
	 * @param WidgetDatabase $widget
	 * @return bool|WidgetDatabase
	 * @throws ValidationException
	 * @throws \KodiCMS\Widgets\Exceptions\WidgetException
	 */
	protected function _create(WidgetDatabase $widget)
	{
		if ($widget->isExists())
		{
			return false;
		}

		$data = [
			'name' => $widget->getName(),
			'description' => $widget->getDescription(),
			'settings' => $widget->getSettings(),
			'type' => $widget->getType(),
			'class' => get_class($widget)
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
	 * @param WidgetDatabase $widget
	 * @return bool
	 * @throws ValidationException
	 */
	public function update(Widget $widget)
	{
		return $this->_update($widget);
	}

	/**
	 * @param WidgetDatabase $widget
	 * @return bool
	 * @throws ValidationException
	 */
	protected function _update(WidgetDatabase $widget)
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
	 * @param WidgetDatabase $widget
	 * @return bool
	 */
	public function delete(Widget $widget)
	{
		return $this->_delete($widget);
	}

	/**
	 * @param WidgetDatabase $widget
	 * @return bool
	 */
	protected function _delete(WidgetDatabase $widget)
	{
		if (!$this->widget->isExists())
		{
			return false;
		}

		WidgetModel::findOrFail($this->widget->getId())->delete();

		return true;
	}
}