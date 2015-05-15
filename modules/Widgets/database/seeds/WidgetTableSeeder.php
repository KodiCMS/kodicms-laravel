<?php namespace KodiCMS\Widgets\database\seeds;

use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Widgets\Contracts\WidgetPaginator;
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
				[
					'header' => 'Sitename'
				],
				100
			],
			[
				'Breadcrumbs',
				null,
				'header',
				'page.breadcrumbs',
				[],
				200
			],
			[
				'Demo data',
				'content.blade',
				'header',
				'html',
				[
					'header' => 'KodiCMS demo site'
				],
				300
			],
			[
				'Content',
				null,
				'content',
				'page.list',
				[
					'header' => 'Page list',
					'include_user_object' => true,
					'page_id' => 0
				],
				100
			],
			[
				'Footer',
				'footer.blade',
				'footer',
				'html',
				[],
				100
			]
		];

		$pages = Page::all();

		foreach($widgets as $data)
		{
			list($name, $template, $block, $type, $settings, $position) = $data;

			$widget = Widget::create([
				'name' => $name,
				'type' => $type,
				'template' => $template,
				'settings' => array_merge([

				], $settings)
			]);

			$placeData = [];
			foreach($pages as $page)
			{
				$placeData[$page->id]['block'] = $block;
				$placeData[$page->id]['position'] = $position;
			}

			if($widget->toWidget() instanceof WidgetPaginator)
			{
				$paginator = Widget::create([
					'name' => 'Pagination for [' . $widget->name . ']',
					'type' => 'paginator',
					'settings' => [
						'linked_widget_id' => $widget->id
					]
				]);

				WidgetManagerDatabase::placeWidgetsOnPages($paginator->id, $placeData);
			}

			WidgetManagerDatabase::placeWidgetsOnPages($widget->id, $placeData);
		}
	}
}
