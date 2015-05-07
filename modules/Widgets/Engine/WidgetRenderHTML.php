<?php namespace KodiCMS\Widgets\Engine;

use View;
use Cache;
use KodiCMS\CMS\Model\File;
use Illuminate\Cache\TaggableStore;
use KodiCMS\Widgets\Contracts\WidgetCacheable;

class WidgetRenderHTML extends WidgetRenderAbstract
{
	public function render()
	{
		$widget = $this->getWidget();
		if ($widget instanceof WidgetCacheable and $widget->isCacheEnabled())
		{
			if (Cache::getFacadeRoot()->store()->getStore() instanceof TaggableStore)
			{
				return Cache::tags($widget->getCacheTags())->remember($widget->getCacheKey(), $widget->getCacheLifetime(), function ()
				{
					return $this->getContent();
				});
			}
			else
			{
				return Cache::remember($widget->getCacheKey(), $widget->getCacheLifetime(), function ()
				{
					return $this->getContent();
				});
			}
		}

		return $this->getContent();
	}

	protected function getContent()
	{
		$widget = $this->getWidget();
		$widget->setParameters($this->parameters);

		$allowHTMLComments = (bool)$widget->getParameter('comments', true);

		$preparedData = $widget->getPreparedData();
		$preparedData['parameters'] = $widget->getParameters();
		$preparedData['widgetId'] = $widget->getId();
		$preparedData['header'] = $widget->getParameter('header');

		$html = '';

		if ($allowHTMLComments)
		{
			$html .= PHP_EOL . "<!--[Widget: {$widget->getName()}]-->" . PHP_EOL;
		}

		$html .= $this->getWidgetTemplate()->toView($preparedData)->render();

		if ($allowHTMLComments)
		{
			$html .= PHP_EOL . "<!--[/Widget: {$widget->getName()}]-->" . PHP_EOL;
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