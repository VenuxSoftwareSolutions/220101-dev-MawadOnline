<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $areas = [
            ['id' => 1, 'name' => 'Abu Dhabi - Capital', 'emirate_id' => 1],
            ['id' => 2, 'name' => 'Al Ain', 'emirate_id' => 1],
            ['id' => 3, 'name' => 'Al shamkha', 'emirate_id' => 1],
            ['id' => 4, 'name' => 'Al shahama', 'emirate_id' => 1],
            ['id' => 5, 'name' => 'Al Falah', 'emirate_id' => 1],
            ['id' => 6, 'name' => 'Baniyas', 'emirate_id' => 1],
            ['id' => 7, 'name' => 'Swehaan', 'emirate_id' => 1],
            ['id' => 8, 'name' => 'Al wathba', 'emirate_id' => 1],
            ['id' => 9, 'name' => 'Al taweelah', 'emirate_id' => 1],
            ['id' => 10, 'name' => 'Liwa Oasis', 'emirate_id' => 1],
            ['id' => 11, 'name' => 'Musaffah', 'emirate_id' => 1],
            ['id' => 12, 'name' => 'Al Dhafra', 'emirate_id' => 1],
            ['id' => 13, 'name' => 'Al Bastakiya', 'emirate_id' => 4],
            ['id' => 14, 'name' => 'Al Karama', 'emirate_id' => 4],
            ['id' => 15, 'name' => 'Bur Dubai', 'emirate_id' => 4],
            ['id' => 16, 'name' => 'Business Bay', 'emirate_id' => 4],
            ['id' => 17, 'name' => 'Downtown Dubai', 'emirate_id' => 4],
            ['id' => 18, 'name' => 'Dubai Marina', 'emirate_id' => 4],
            ['id' => 19, 'name' => 'Al Butina', 'emirate_id' => 3],
            ['id' => 20, 'name' => 'Al Gharayen', 'emirate_id' => 3],
            ['id' => 21, 'name' => 'Al Heera', 'emirate_id' => 3],
            ['id' => 22, 'name' => 'Al Qasba', 'emirate_id' => 3],
            ['id' => 23, 'name' => 'Al Riqa', 'emirate_id' => 3],
            ['id' => 24, 'name' => 'Al Sharq', 'emirate_id' => 3],
            ['id' => 25, 'name' => 'Halwan', 'emirate_id' => 3],
            ['id' => 26, 'name' => 'Industrial areas', 'emirate_id' => 3],
            ['id' => 27, 'name' => 'Mughadir', 'emirate_id' => 3],
            ['id' => 28, 'name' => 'Wasit', 'emirate_id' => 3],
            ['id' => 29, 'name' => 'Al Hamrah', 'emirate_id' => 7],
            ['id' => 30, 'name' => 'Al Khor', 'emirate_id' => 7],
            ['id' => 31, 'name' => 'Al Maidan', 'emirate_id' => 7],
            ['id' => 32, 'name' => 'Latain', 'emirate_id' => 7],
            ['id' => 33, 'name' => 'Defence Camp', 'emirate_id' => 7],
            ['id' => 34, 'name' => 'Industrial Area', 'emirate_id' => 7],
            ['id' => 35, 'name' => 'Old Town Area', 'emirate_id' => 7],
            ['id' => 36, 'name' => 'Al Ramlah', 'emirate_id' => 7],
            ['id' => 37, 'name' => 'Thoban', 'emirate_id' => 5],
            ['id' => 38, 'name' => 'Al Faseel', 'emirate_id' => 5],
            ['id' => 39, 'name' => 'Al Hayl', 'emirate_id' => 5],
            ['id' => 40, 'name' => 'Dibba Al Fujairah', 'emirate_id' => 5],
            ['id' => 41, 'name' => 'Corniche', 'emirate_id' => 5],
            ['id' => 42, 'name' => 'Dhadna', 'emirate_id' => 5],
            ['id' => 43, 'name' => 'Sakamkam', 'emirate_id' => 5],
            ['id' => 44, 'name' => 'Mirbah', 'emirate_id' => 5],
            ['id' => 45, 'name' => 'Oraibi', 'emirate_id' => 6],
            ['id' => 46, 'name' => 'Al Nakheel', 'emirate_id' => 6],
            ['id' => 47, 'name' => 'Al Hudayba', 'emirate_id' => 6],
            ['id' => 48, 'name' => 'Al Maereed', 'emirate_id' => 6],
            ['id' => 49, 'name' => 'Al Maa\'moura', 'emirate_id' => 6],
            ['id' => 50, 'name' => 'Golan and Shaa\'', 'emirate_id' => 6],
            ['id' => 51, 'name' => 'Al Bustan', 'emirate_id' => 2],
            ['id' => 52, 'name' => 'Al Hamidiya', 'emirate_id' => 2],
            ['id' => 53, 'name' => 'Al Jurf', 'emirate_id' => 2],
            ['id' => 54, 'name' => 'Al Maemiah', 'emirate_id' => 2],
            ['id' => 55, 'name' => 'Musharif', 'emirate_id' => 2],
            ['id' => 56, 'name' => 'Rumailah', 'emirate_id' => 2],
            ['id' => 57, 'name' => 'Al Nuaimia', 'emirate_id' => 2],
            ['id' => 58, 'name' => 'Muwaihat', 'emirate_id' => 2],
        ];


        // Inserting records without created_at and updated_at
        DB::table('areas')->insert($areas);
    }
}
