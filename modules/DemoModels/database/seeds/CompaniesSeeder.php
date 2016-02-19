<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Company;

class CompaniesSeeder extends Seeder
{

    public function run()
    {
        Company::truncate();

        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            Company::create([
                'title'   => $faker->unique()->company,
                'address' => $faker->streetAddress,
                'phone'   => $faker->phoneNumber,
            ]);
        }
    }

}
