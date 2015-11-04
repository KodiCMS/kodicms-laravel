<?php

namespace KodiCMS\Widgets\Engine;

use Cache;
use Illuminate\View\View;
use Illuminate\Cache\TaggableStore;
use KodiCMS\Widgets\Helpers\ViewPHP;
use KodiCMS\Widgets\Manager\WidgetManager;
use KodiCMS\Widgets\Model\SnippetCollection;
use KodiCMS\Widgets\Contracts\WidgetCacheable;

class WidgetRenderHTML extends WidgetRenderAbstract
{
    public function render()
    {
        $widget = $this->getWidget();

        if (method_exists($widget, 'onRender')) {
            $widget->onRender($this);
        }

        if ($widget instanceof WidgetCacheable and $widget->isCacheEnabled()) {
            if (Cache::getFacadeRoot()->store()->getStore() instanceof TaggableStore) {
                return Cache::tags($widget->getCacheTags())->remember($widget->getCacheKey(), $widget->getCacheLifetime(), function (
                ) {
                    return $this->getContent();
                });
            } else {
                return Cache::remember($widget->getCacheKey(), $widget->getCacheLifetime(), function () {
                    return $this->getContent();
                });
            }
        }

        return $this->getContent();
    }

    /**
     * @return string
     */
    protected function getContent()
    {
        $widget = $this->getWidget();
        $widget->setParameters($this->parameters);

        $preparedData = $widget->prepareData();
        $preparedData['parameters'] = $widget->getParameters();

        $allowHTMLComments = (bool) $widget->getParameter('comments', true);

        $preparedData['widgetId'] = $widget->getId();
        $preparedData['settings'] = $widget->getSettings();
        $preparedData['header'] = $widget->getSetting('header');

        $preparedData['relatedWidgets'] = WidgetManager::buildWidgetCollection(
            $widget->getRalatedWidgets()
        );

        $html = '';

        if ($allowHTMLComments) {
            $html .= PHP_EOL."<!--[Widget: {$widget->getName()}]-->".PHP_EOL;
        }

        $html .= $this->getWidgetTemplate($preparedData)->render();

        if ($allowHTMLComments) {
            $html .= PHP_EOL."<!--[/Widget: {$widget->getName()}]-->".PHP_EOL;
        }

        return $html;
    }

    /**
     * @param array $preparedData
     *
     * @return View
     */
    protected function getWidgetTemplate(array $preparedData)
    {
        $template = $this->getWidget()->getFrontendTemplate();

        // Если не указан шаблон и указан шаблон по умолчанию
        if (is_null($template) and ! is_null($template = $this->getWidget()->getDefaultFrontendTemplate())) {
            if ($template instanceof View) {
                return $template->with($preparedData);
            }

            return view($template)->with($preparedData);
        }

        if (! is_null($template)) {
            if ($template instanceof View) {
                return $template->with($preparedData);
            } elseif ($template instanceof ViewPHP) {
                return $template->with($preparedData);
            }

            if ($snippet = (new SnippetCollection)->findFile($template)) {
                return $snippet->toView($preparedData);
            }
        }

        return view('widgets::widgets.default', $preparedData);
    }
}
