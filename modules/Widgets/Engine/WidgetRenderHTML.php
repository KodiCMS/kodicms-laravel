<?php namespace KodiCMS\Widgets\Engine;

use KodiCMS\CMS\Model\File;
use KodiCMS\Widgets\Contracts\WidgetCacheable;
use View;

class WidgetRenderHTML extends WidgetRenderAbstract
{
	public function render()
	{
		$widget = $this->getWidget();
		$widget->setParameters($this->parameters);

		if ($widget instanceof WidgetCacheable)
		{
			// TODO: реализовать кеширование данных
		}

		return $this->getContent();
	}

	protected function getContent()
	{
		$widget = $this->getWidget();

		$allowHTMLComments = (bool)$widget->getParameter('comments', true);

		$preparedData = $widget->getPreparedData();
		$preparedData['parameters'] = $widget->getParameters();
		$preparedData['widget_id'] = $widget->getId();
		$preparedData['header'] = $widget->getParameter('header');

		$html = '';

		if ($allowHTMLComments)
		{
			$html .= "<!--[Widget: {$widget->getName()}]-->";
		}

		$html .= (string) $this->getWidgetTemplate()->toView($preparedData);

		if ($allowHTMLComments)
		{
			$html .= "<!--[/Widget: {$widget->getName()}]-->";
		}

		return $html;
	}

	/**
	 * @return File
	 */
	protected function getWidgetTemplate()
	{
		$template = $this->getWidget()->getFrontendTemplate();

		if (is_null($template))
		{
			$template = $this->getWidget()->getDefaultFrontendTemplate();
		}

		$template = 'test.blade';

		$snippet = new File($template, snippets_path(), true);

		return $snippet;
	}
}