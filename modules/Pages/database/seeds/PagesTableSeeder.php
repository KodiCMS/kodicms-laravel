<?php namespace KodiCMS\Pages\Database\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\Page;

class PagesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('pages')->truncate();

		$rootPage = Page::create([
			'title' => 'Home',
			'breadcrumb' => 'Home',
			'published_at' => new Carbon()
		]);

		$pages = [
			[
				'title' => 'News',
				'breadcrumb' => 'News',
				'slug' => 'news',
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
			$page->children()->save(new Page([
				'title' => 'Article',
				'breadcrumb' => 'Article',
				'slug' => 'article',
				'published_at' => new Carbon()
			]));
		}
	}
}
