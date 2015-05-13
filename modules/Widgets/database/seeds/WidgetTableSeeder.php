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
				'KodiCMS\Pages\Widget\PageMenu',
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
				'KodiCMS\Pages\Widget\PageBreadcrumbs',
				[],
				200
			],
			[
				'Content',
				'content.blade',
				'content',
				'html',
				'KodiCMS\Widgets\Widget\HTML',
				[
					'header' => 'Content'
				],
				0
			],
			[
				'Content',
				null,
				'content',
				'page.list',
				'KodiCMS\Pages\Widget\PageList',
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
				'KodiCMS\Widgets\Widget\HTML',
				[],
				100
			]
		];

		$pages = Page::all();

		foreach($widgets as $data)
		{
			list($name, $template, $block, $type, $class, $settings, $position) = $data;

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
				$placeData[$page->id]['position'] = $position;
			}

			if($widget->toWidget() instanceof WidgetPaginator)
			{
				$paginator = Widget::create([
					'name' => 'Pagination for [' . $widget->name . ']',
					'type' => 'paginator',
					'class' => '\KodiCMS\Widgets\Widget\Paginator',
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
