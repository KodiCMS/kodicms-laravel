<?php

namespace Modules\DemoModels\database\seeds;

use Illuminate\Database\Seeder;
use Modules\DemoModels\Model\Company;
use Modules\DemoModels\Model\Contact;

class CompanyContactSeeder extends Seeder
{

    public function run()
    {
        $contacts  = Contact::all();
        $companies = Company::all();

        for ($i = 0; $i < 20; $i++) {
            try {
                $contacts->random()->companies()->attach($companies->random());
            } catch (\Exception $e) {
            }
        }
    }

}
