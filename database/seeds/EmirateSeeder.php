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
        // $emirates = [
        //     ['id' => '1','name' => 'Abu Dhabi'],
        //     ['id' => '2','name' => 'Ajman'],
        //     ['id' => '3','name' => 'Sharjah'],
        //     ['id' => '4','name' => 'Dubai'],
        //     ['id' => '5','name' => 'Fujairah'],
        //     ['id' => '6','name' => 'Ras Al Khaimah'],
        //     ['id' => '7','name' => 'Umm Al-Quwain'],
        // ];

        $emirates = [
            ['id' => '1', 'name' => json_encode(['en' => 'Abu Dhabi', 'ae' => 'أبو ظبي'], JSON_UNESCAPED_UNICODE)],
            ['id' => '2', 'name' => json_encode(['en' => 'Ajman', 'ae' => 'عجمان'], JSON_UNESCAPED_UNICODE)],
            ['id' => '3', 'name' => json_encode(['en' => 'Sharjah', 'ae' => 'الشارقة'], JSON_UNESCAPED_UNICODE)],
            ['id' => '4', 'name' => json_encode(['en' => 'Dubai', 'ae' => 'دبي'], JSON_UNESCAPED_UNICODE)],
            ['id' => '5', 'name' => json_encode(['en' => 'Fujairah', 'ae' => 'الفجيرة'], JSON_UNESCAPED_UNICODE)],
            ['id' => '6', 'name' => json_encode(['en' => 'Ras Al Khaimah', 'ae' => 'رأس الخيمة'], JSON_UNESCAPED_UNICODE)],
            ['id' => '7', 'name' => json_encode(['en' => 'Umm Al-Quwain', 'ae' => 'أم القيوين'], JSON_UNESCAPED_UNICODE)],
        ];


        // Insert the emirates into the 'emirates' table
        DB::table('emirates')->insert($emirates);
    }
}
