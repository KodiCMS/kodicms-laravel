<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Contact;

class CountriesSeeder extends Seeder
{

    public function run()
    {
        Contact::truncate();
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            Contact::create([
                'title' => $faker->unique()->country,
            ]);
        }
    }

}
