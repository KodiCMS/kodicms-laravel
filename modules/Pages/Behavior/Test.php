<?php namespace KodiCMS\Pages\Behavior;

class Test extends BehaviorAbstract
{
	/**
	 * @return array
	 */
	public function routeList()
	{
		return [
			'/<id>' => [
				'regex' => [
					'id' => '[0-9]+'
				]
			],
			'/<slug>' => [
				'regex' => [
					'slug' => '.*'
				]
			]
		];
	}
}