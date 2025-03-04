<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_settings')->insert([
            ['type' => 'mwd_commission_percentage',
                'value' => 0.13],
            ['type' => 'mwd_commission_percentage_vat',
                'value' => 0.05],
        ]);
    }
}
