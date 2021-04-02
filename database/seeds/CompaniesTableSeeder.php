<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            [
                'company_name'=> 'Sony',
                'street_address'=> '住所内容1',
            ],
            [
                'company_name'=> 'Panasonic',
                'street_address'=> '住所内容2',
            ],
        ]);
    }
}
