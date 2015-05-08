<?php namespace KodiCMS\Widgets\Widget;

use KodiCMS\Widgets\Contracts\WidgetDatabase;

abstract class DatabaseDecorator extends Decorator implements WidgetDatabase
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @return bool
	 */
	public function isExists()
	{
		return $this->getId() > 0;
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @throws WidgetException
	 */
	public function setId($id)
	{
		if ($this->isExists())
		{
			// TODO: написать правильный текст
			throw new WidgetException('You can\'t change widget id');
		}

		$this->id = (int) $id;
	}
}