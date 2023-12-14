<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmirateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emirates = [
            ['id' => '1','name' => 'Abu Dhabi'],
            ['id' => '2','name' => 'Ajman'],
            ['id' => '3','name' => 'Sharjah'],
            ['id' => '4','name' => 'Dubai'],
            ['id' => '5','name' => 'Fujairah'],
            ['id' => '6','name' => 'Ras Al Khaimah'],
            ['id' => '7','name' => 'Umm Al-Quwain'],
        ];

        // Insert the emirates into the 'emirates' table
        DB::table('emirates')->insert($emirates);
    }
}
