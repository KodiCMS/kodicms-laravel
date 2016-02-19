<?php

namespace Modules\DemoModels\database\seeds;

use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call(CountriesSeeder::class);
        $this->call(CompaniesSeeder::class);
        $this->call(ContactsSeeder::class);
        $this->call(CompanyContactSeeder::class);
        $this->call(PagesSeeder::class);
        $this->call(NewsSeeder::class);
        $this->call(PostsSeeder::class);
        $this->call(FormsSeeder::class);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
