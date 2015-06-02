<?php namespace KodiCMS\Pages\Behavior;

class Test extends BehaviorAbstract
{
	/**
	 * @var null|string
	 */
	protected $settingsTemplate = 'pages::behavior.test';

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