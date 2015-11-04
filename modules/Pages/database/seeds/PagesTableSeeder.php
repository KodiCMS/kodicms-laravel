<?php

namespace KodiCMS\Pages\database\seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Model\Page;
use KodiCMS\Pages\Model\PagePart;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Page::truncate();
        PagePart::truncate();

        $rootPage = Page::create([
            'title'        => 'Home',
            'breadcrumb'   => 'Home',
            'slug'         => '',
            'layout_file'  => 'normal.blade',
            'published_at' => new Carbon(),
        ]);

        $page = new Page([
            'title'        => 'Page not found',
            'breadcrumb'   => 'Page not found',
            'slug'         => 'page-not-found',
            'behavior'     => 'page.not.found',
            'status'       => FrontendPage::STATUS_HIDDEN,
            'published_at' => new Carbon(),
        ]);

        $rootPage->children()->save($page);

        $page->parts()->save(new PagePart([
            'name'    => 'content',
            'content' => '<h1>Page not found</h1>',
            'wysiwyg' => 'ace',
        ]));
    }
}
