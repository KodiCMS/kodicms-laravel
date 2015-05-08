<?php namespace KodiCMS\Widgets\database\seeds;

use Illuminate\Database\Seeder;
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

		Widget::create([
			'name' => 'Шапка сайта',
			'description' => 'Меню, логотип',
			'type' => 'html',
			'class' => '\KodiCMS\Widgets\Widget\HTML',
			'settings' => [
				'header' => 'Шапка сайта'
			]
		]);

		Widget::create([
			'name' => 'Тестовый обработчик',
			'type' => 'handler',
			'class' => '\KodiCMS\Widgets\Widget\Handler',
		]);

		Widget::create([
			'name' => 'Тест 1',
			'type' => 'http',
			'class' => '\KodiCMS\Widgets\Widget\HTTP'
		]);
	}
}
