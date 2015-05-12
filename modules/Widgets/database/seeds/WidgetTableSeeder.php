<?php namespace KodiCMS\Widgets\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\Page;
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
				null,
				'header',
				'page.menu',
				'KodiCMS\Pages\Widget\PageMenu',
				['include_children' => true, 'header' => 'Sitename']
			],
			[
				'Breadcrumbs',
				null,
				'content.before',
				'page.breadcrumbs',
				'KodiCMS\Pages\Widget\PageBreadcrumbs',
				[]
			],
			[
				'Content',
				'content.blade',
				'content',
				'html',
				'KodiCMS\Widgets\Widget\HTML',
				['header' => 'Content']
			],
			[
				'Footer',
				'footer.blade',
				'footer',
				'html',
				'KodiCMS\Widgets\Widget\HTML',
				[]
			]
		];

		$pages = Page::all();

		foreach($widgets as $data)
		{
			list($name, $template, $block, $type, $class, $settings) = $data;

			$widget = Widget::create([
				'name' => $name,
				'type' => $type,
				'template' => $template,
				'class' => $class,
				'settings' => array_merge([

				], $settings)
			]);

			$placeData = [];
			foreach($pages as $page)
			{
				$placeData[$page->id]['block'] = $block;
			}

			WidgetManagerDatabase::placeWidgetsOnPages($widget->id, $placeData);
		}
	}
}
