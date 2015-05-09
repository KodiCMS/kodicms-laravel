<?php namespace KodiCMS\Pages\Helpers;

use KodiCMS\Widgets\Collection\WidgetCollection;
use KodiCMS\Widgets\Contracts\Widget;
use KodiCMS\Widgets\Engine\WidgetRenderHTML;
use View;

class Block
{

	/**
	 * @var WidgetCollection
	 */
	protected $collection;

	/**
	 * @param WidgetCollection $collection
	 */
	public function __construct(WidgetCollection $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * Проверка блока на наличие в нем виджетов
	 *
	 * @param type string|array
	 * @return boolean
	 */
	public function hasWidgets($name)
	{
		if (!is_array($name))
		{
			$name = [$name];
		}

		// TODO: реализовать получение списка виджетов для указанного блока
		$blocks = [];
		//$blocks = ....;

		foreach ($name as $block)
		{
			if (in_array($block, $blocks))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Метод служит для разметки выводимых блоков на странице
	 *
	 * @param string $name
	 * @param array $params
	 */
	public function run($name, array $params = [])
	{
		if ($name == 'PRE' OR $name == 'POST')
		{
			return;
		}

		$widgets = static::getWidgetsByBlock($name, $params);

		foreach ($widgets as $widget)
		{
			$widget = $widget->getObject();

			if ($widget instanceof View)
			{
				echo $widget->render();
			}
			else if ($widget instanceof Widget)
			{
				echo (new WidgetRenderHTML($widget))->render();
			}
		}
	}

	/**
	 * Получение виджетов блока без вывода.
	 *
	 * Для вывода данных блока
	 *
	 *        $widget = Block::get('block_name', $params);
	 *        if(is_array($widget))
	 *        {
	 *            foreach($widget as $data)
	 *            {
	 *                echo $data;
	 *            }
	 *        }
	 *        else
	 *            echo $widget;
	 *
	 * @param string $name
	 * @param array $params Дополнительные параметры доступные в виджете
	 * @return array
	 */
	public function getWidgetsByBlock($name, array $params = [])
	{
		$widgets = $this->collection->getWidgetsByBlock($name);

		foreach ($widgets as $widget)
		{
			$widget = $widget->getObject();
			if ($widget instanceof View)
			{
				$widget->setParameters('params', $params);
			}
			else if ($widget instanceof Widget)
			{
				$widget->setParameters($params);
			}
		}

		return $widgets;
	}

	/**
	 * Блок типа def служит для помещения в него виджетов без вывода.
	 * Необходим в том случае, если необходимо на странице вывести виджет
	 * внутри другого виджета, но без разметки блока в шаблоне, в него не получится
	 * поместить виджет.
	 *
	 * Т.е. в основном шаблоне в самом низу мы указываем, например:
	 *
	 *        Block::def('block_name_def');
	 *
	 * Теперь в него можно поместить виджет, далее в шаблоне виджета, в котором
	 * мы хотим его вывести пишем:
	 *
	 *        Block::run('block_name_def');
	 *
	 * @param string $name
	 */
	public function def($name) {}
}