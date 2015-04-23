<?php namespace KodiCMS\Pages\Behavior;

// TODO: удалить
class Test extends Decorator
{
	/**
	 * @return array
	 */
	public function routeList()
	{
		return [
			'/<id>' => array(
				'regex' => array(
					'id' => '[0-9]+'
				)
			),
			'/<slug>' => array(
				'regex' => array(
					'slug' => '.*'
				)
			)
		];
	}

	public function execute()
	{

	}
}