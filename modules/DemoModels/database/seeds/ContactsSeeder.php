<?php

namespace Modules\DemoModels\database\seeds;

use Faker\Factory;
use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Contact;
use Modules\DemoModels\Model\Country;
use Symfony\Component\Finder\Finder;

class ContactsSeeder extends Seeder
{

    public function run()
    {
        Contact::truncate();
        $uploads  = public_path('images/uploads');
        $filesObj = Finder::create()->files()->in($uploads);
        $files    = [];
        foreach ($filesObj as $file) {
            $files[] = $file->getFilename();
        }
        $countries = Country::lists('id')->all();

        $faker = Factory::create();
        for ($i = 0; $i < 20; $i++) {
            $image = $faker->optional()->randomElement($files);

            Contact::create([
                'firstName'  => $faker->firstName,
                'lastName'   => $faker->lastName,
                'birthday'   => $faker->dateTimeThisCentury,
                'phone'      => $faker->phoneNumber,
                'address'    => $faker->address,
                'country_id' => $faker->optional()->randomElement($countries),
                'comment'    => $faker->paragraph(5),
                'photo'      => is_null($image) ? $image : ('images/uploads/'.$image),
                'height'     => $faker->randomNumber(2, true) + 100,
            ]);
        }
    }

}
