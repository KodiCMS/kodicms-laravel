<?php namespace KodiCMS\Widgets\Contracts;

interface Widget {

	public function __construct($id, $type, $name, $description = '');

	public function getType();
	public function getName();
	public function getDescription();
	public function getId();

	public function render(WidgetRenderEngine $engine);
}