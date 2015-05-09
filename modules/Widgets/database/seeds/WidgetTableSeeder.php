<?php namespace KodiCMS\Widgets\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Widgets\Manager\WidgetManagerDatabase;
use KodiCMS\Widgets\Model\Widget;

class WidgetTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('widgets')->truncate();

		$widgets = [
			[
				'Header menu',
				'header.blade',
				'header'
			],
			'Left menu',
			[
				'Footer menu',
				null,
				'footer'
			],
			[
				'Content',
				'content.blade',
				'content'
			]
		];

		foreach($widgets as $name)
		{
			if(is_array($name))
			{
				list($name, $template, $block) = $name;
			} else {
				$template = null;
				$block = null;
			}
			$widget = Widget::create([
				'name' => $name,
				'type' => 'html',
				'template' => $template,
				'class' => '\KodiCMS\Widgets\Widget\HTML',
				'settings' => [
					'header' => $name
				]
			]);

			WidgetManagerDatabase::placeWidgetsOnPages($widget->id, [
				1 => [
					'block' =>$block
				]
			]);
		}

		Widget::create([
			'name' => 'Test handler',
			'type' => 'handler',
			'class' => '\KodiCMS\Widgets\Widget\Handler',
		]);

		Widget::create([
			'name' => 'Test corrupted widget',
			'type' => 'http',
			'class' => '\KodiCMS\Widgets\Widget\HTTP'
		]);
	}
}
