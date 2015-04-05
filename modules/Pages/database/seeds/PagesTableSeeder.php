<?php namespace KodiCMS\Pages\Database\Seeds;

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
			'breadcrumb' => 'Home'
		]);

		$pages = [
			[
				'title' => 'News',
				'breadcrumb' => 'News',
				'slug' => 'news',
			],
			[
				'title' => 'Blog',
				'breadcrumb' => 'Blog',
				'slug' => 'blog',
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
			]));
		}
	}
}
