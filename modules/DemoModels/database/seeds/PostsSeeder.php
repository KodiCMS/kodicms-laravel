<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Post;

class PostsSeeder extends Seeder
{

    public function run()
    {
        Post::truncate();

        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $post = Post::create([
                'title' => $faker->sentence(5),
                'text'  => $faker->paragraph(5),
            ]);
            if (mt_rand(0, 10) < 3) {
                $post->delete();
            }
        }
    }

}
