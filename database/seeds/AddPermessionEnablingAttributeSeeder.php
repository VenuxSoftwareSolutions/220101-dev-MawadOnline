<?php

namespace Database\seeds;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddPermessionEnablingAttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'enabling_product_attribute',
            'section' => 'product_attribute',
            'guard_name' => 'web'
        ]);
    }
}
