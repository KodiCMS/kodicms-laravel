<?php namespace KodiCMS\Widgets\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\Page;
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
			'parameters' => [
				'header' => 'Шапка сайта'
			],
			'settings' => []
		]);

		Widget::create([
			'name' => 'Сломанный виджет',
			'type' => 'http',
			'class' => '\KodiCMS\Widgets\Widget\HTTP',
			'parameters' => [],
			'settings' => []
		]);
	}
}
