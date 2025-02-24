<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_settings')->insert([
            'type'       => 'max_reviews_per_product',
            'value'      => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
