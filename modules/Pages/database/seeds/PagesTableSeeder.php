<?php namespace KodiCMS\Pages\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart;

class PagesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('pages')->truncate();
		\DB::table('page_parts')->truncate();

		$rootPage = Page::create([
			'title' => 'Home',
			'breadcrumb' => 'Home',
			'slug' => '',
			'layout_file' => 'normal.blade',
			'published_at' => new Carbon()
		]);

		$pages = [
			[
				'title' => 'News',
				'breadcrumb' => 'News',
				'slug' => 'news',
				'behavior' => 'test',
				'published_at' => new Carbon()
			],
			[
				'title' => 'Blog',
				'breadcrumb' => 'Blog',
				'slug' => 'blog',
				'published_at' => new Carbon()
			]
		];

		foreach($pages as $i => $page)
		{
			$pages[$i] = new Page($page);
		}

		$rootPage->children()->saveMany($pages);

		foreach($pages as $page)
		{
			foreach(range(1, 30) as $i)
			{
				$title = str_singular($page->title) . ' ' . $i;
				$page->children()->save(new Page([
					'title' => $title,
					'breadcrumb' => $title,
					'slug' => 'article' . $i,
					'published_at' => new Carbon()
				]));
			}
		}

		$page = new Page([
			'title' => 'About',
			'breadcrumb' => 'About',
			'slug' => 'about',
			'published_at' => new Carbon(),
			'is_redirect' => TRUE,
			'redirect_url' => url('about/us')
		]);

		$rootPage->children()->save($page);

		$page->children()->save(new Page([
			'title' => 'Us',
			'breadcrumb' => 'Us',
			'slug' => 'us',
			'published_at' => new Carbon()
		]));

		$page = new Page([
			'title' => 'Page not found',
			'breadcrumb' => 'Page not found',
			'slug' => 'page-not-found',
			'behavior' => 'page.not.found',
			'status' => FrontendPage::STATUS_HIDDEN,
			'published_at' => new Carbon()
		]);

		$rootPage->children()->save($page);

		$page->parts()->save(new PagePart([
			'name' => 'content',
			'content' => '<h1>Page not found</h1>',
			'content_html' => '<h1>Page not found</h1>',
			'wysiwyg' => 'ace'
		]));
	}
}
