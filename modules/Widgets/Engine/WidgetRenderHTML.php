<?php namespace KodiCMS\Widgets\Engine;

use Illuminate\View\View;
use Cache;
use Illuminate\Cache\TaggableStore;
use KodiCMS\Widgets\Model\SnippetCollection;
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

		$widget->prepareData();

		$allowHTMLComments = (bool)$widget->getParameter('comments', true);

		$preparedData = $widget->getParameters();
		$preparedData['widgetId'] = $widget->getId();
		$preparedData['settings'] = $widget->getSettings();
		$preparedData['header'] = $widget->getSetting('header');

		$html = '';

		if ($allowHTMLComments)
		{
			$html .= PHP_EOL . "<!--[Widget: {$widget->getName()}]-->" . PHP_EOL;
		}

		$html .= $this->getWidgetTemplate($preparedData)->render();

		if ($allowHTMLComments)
		{
			$html .= PHP_EOL . "<!--[/Widget: {$widget->getName()}]-->" . PHP_EOL;
		}

		return $html;
	}

	/**
	 * @return File|View
	 */
	protected function getWidgetTemplate($preparedData)
	{
		$template = $this->getWidget()->getFrontendTemplate();

		if (is_null($template))
		{
			$template = $this->getWidget()->getDefaultFrontendTemplate();
		}

		if (!is_null($template))
		{
			if ($template instanceof View)
			{
				return $template->with($preparedData);
			}

			$snippet = (new SnippetCollection)->findFile($template);
			return $snippet->toView($preparedData);
		}

		return view('widgets::widgets.default', $preparedData);
	}
}