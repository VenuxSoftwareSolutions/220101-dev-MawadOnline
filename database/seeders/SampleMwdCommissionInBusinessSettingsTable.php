<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SampleMwdCommissionInBusinessSettingsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business_settings')->insert([
          ['type' => 'sample_price_mwd_commission',
              'value' => "on"],
        ]);
    }
}
