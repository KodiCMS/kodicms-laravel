<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Form;
use Symfony\Component\Finder\Finder;

class FormsSeeder extends Seeder
{

    public function run()
    {
        Form::truncate();
        $uploads  = public_path('images/uploads');

        $filesObj = Finder::create()->files()->in($uploads);
        $files    = [];
        foreach ($filesObj as $file) {
            $files[] = $file->getFilename();
        }

        $faker = Factory::create();
        for ($i = 0; $i < 5; $i++) {
            $image       = $faker->optional()->randomElement($files);
            $images      = [];
            $imagesCount = mt_rand(0, 3);
            for ($j = 0; $j < $imagesCount; $j++) {
                $img      = $faker->randomElement($files);
                $images[] = 'images/uploads/'.$img;
            }

            Form::create([
                'title'     => $faker->sentence(4),
                'textaddon' => $faker->sentence(2),
                'checkbox'  => $faker->boolean(),
                'date'      => $faker->date(),
                'time'      => $faker->time(),
                'timestamp' => $faker->dateTime,
                'image'     => is_null($image) ? $image : ('images/uploads/'.$image),
                'images'    => $images,
                'select'    => $faker->optional()->randomElement([1, 2, 3]),
                'textarea'  => $faker->paragraph(5),
                'ckeditor'  => $faker->paragraph(5),
            ]);
        }
    }

}
