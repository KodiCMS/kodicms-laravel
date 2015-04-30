<?php namespace KodiCMS\Widgets\Contracts;

interface WidgetStorage
{
	public function create(Widget $widget);
	public function update(Widget $widget);
	public function delete(Widget $widget);
}