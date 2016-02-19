<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\News;

class NewsSeeder extends Seeder
{

    public function run()
    {
        News::truncate();
        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            News::create([
                'title'     => $faker->unique()->sentence(4),
                'date'      => $faker->dateTimeThisCentury,
                'published' => $faker->boolean(),
                'text'      => $faker->paragraph(5),
            ]);
        }
    }

}
