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
        // $areas = [
        //     ['id' => 1, 'name' => 'Abu Dhabi - Capital', 'emirate_id' => 1],
        //     ['id' => 2, 'name' => 'Al Ain', 'emirate_id' => 1],
        //     ['id' => 3, 'name' => 'Al shamkha', 'emirate_id' => 1],
        //     ['id' => 4, 'name' => 'Al shahama', 'emirate_id' => 1],
        //     ['id' => 5, 'name' => 'Al Falah', 'emirate_id' => 1],
        //     ['id' => 6, 'name' => 'Baniyas', 'emirate_id' => 1],
        //     ['id' => 7, 'name' => 'Swehaan', 'emirate_id' => 1],
        //     ['id' => 8, 'name' => 'Al wathba', 'emirate_id' => 1],
        //     ['id' => 9, 'name' => 'Al taweelah', 'emirate_id' => 1],
        //     ['id' => 10, 'name' => 'Liwa Oasis', 'emirate_id' => 1],
        //     ['id' => 11, 'name' => 'Musaffah', 'emirate_id' => 1],
        //     ['id' => 12, 'name' => 'Al Dhafra', 'emirate_id' => 1],
        //     ['id' => 13, 'name' => 'Al Bastakiya', 'emirate_id' => 4],
        //     ['id' => 14, 'name' => 'Al Karama', 'emirate_id' => 4],
        //     ['id' => 15, 'name' => 'Bur Dubai', 'emirate_id' => 4],
        //     ['id' => 16, 'name' => 'Business Bay', 'emirate_id' => 4],
        //     ['id' => 17, 'name' => 'Downtown Dubai', 'emirate_id' => 4],
        //     ['id' => 18, 'name' => 'Dubai Marina', 'emirate_id' => 4],
        //     ['id' => 19, 'name' => 'Al Butina', 'emirate_id' => 3],
        //     ['id' => 20, 'name' => 'Al Gharayen', 'emirate_id' => 3],
        //     ['id' => 21, 'name' => 'Al Heera', 'emirate_id' => 3],
        //     ['id' => 22, 'name' => 'Al Qasba', 'emirate_id' => 3],
        //     ['id' => 23, 'name' => 'Al Riqa', 'emirate_id' => 3],
        //     ['id' => 24, 'name' => 'Al Sharq', 'emirate_id' => 3],
        //     ['id' => 25, 'name' => 'Halwan', 'emirate_id' => 3],
        //     ['id' => 26, 'name' => 'Industrial areas', 'emirate_id' => 3],
        //     ['id' => 27, 'name' => 'Mughadir', 'emirate_id' => 3],
        //     ['id' => 28, 'name' => 'Wasit', 'emirate_id' => 3],
        //     ['id' => 29, 'name' => 'Al Hamrah', 'emirate_id' => 7],
        //     ['id' => 30, 'name' => 'Al Khor', 'emirate_id' => 7],
        //     ['id' => 31, 'name' => 'Al Maidan', 'emirate_id' => 7],
        //     ['id' => 32, 'name' => 'Latain', 'emirate_id' => 7],
        //     ['id' => 33, 'name' => 'Defence Camp', 'emirate_id' => 7],
        //     ['id' => 34, 'name' => 'Industrial Area', 'emirate_id' => 7],
        //     ['id' => 35, 'name' => 'Old Town Area', 'emirate_id' => 7],
        //     ['id' => 36, 'name' => 'Al Ramlah', 'emirate_id' => 7],
        //     ['id' => 37, 'name' => 'Thoban', 'emirate_id' => 5],
        //     ['id' => 38, 'name' => 'Al Faseel', 'emirate_id' => 5],
        //     ['id' => 39, 'name' => 'Al Hayl', 'emirate_id' => 5],
        //     ['id' => 40, 'name' => 'Dibba Al Fujairah', 'emirate_id' => 5],
        //     ['id' => 41, 'name' => 'Corniche', 'emirate_id' => 5],
        //     ['id' => 42, 'name' => 'Dhadna', 'emirate_id' => 5],
        //     ['id' => 43, 'name' => 'Sakamkam', 'emirate_id' => 5],
        //     ['id' => 44, 'name' => 'Mirbah', 'emirate_id' => 5],
        //     ['id' => 45, 'name' => 'Oraibi', 'emirate_id' => 6],
        //     ['id' => 46, 'name' => 'Al Nakheel', 'emirate_id' => 6],
        //     ['id' => 47, 'name' => 'Al Hudayba', 'emirate_id' => 6],
        //     ['id' => 48, 'name' => 'Al Maereed', 'emirate_id' => 6],
        //     ['id' => 49, 'name' => 'Al Maa\'moura', 'emirate_id' => 6],
        //     ['id' => 50, 'name' => 'Golan and Shaa\'', 'emirate_id' => 6],
        //     ['id' => 51, 'name' => 'Al Bustan', 'emirate_id' => 2],
        //     ['id' => 52, 'name' => 'Al Hamidiya', 'emirate_id' => 2],
        //     ['id' => 53, 'name' => 'Al Jurf', 'emirate_id' => 2],
        //     ['id' => 54, 'name' => 'Al Maemiah', 'emirate_id' => 2],
        //     ['id' => 55, 'name' => 'Musharif', 'emirate_id' => 2],
        //     ['id' => 56, 'name' => 'Rumailah', 'emirate_id' => 2],
        //     ['id' => 57, 'name' => 'Al Nuaimia', 'emirate_id' => 2],
        //     ['id' => 58, 'name' => 'Muwaihat', 'emirate_id' => 2],
        // ];
        $areas = [
            ['id' => 1, 'name' => json_encode(['en' => 'Abu Dhabi - Capital', 'ae' => 'أبو ظبي - العاصمة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 2, 'name' => json_encode(['en' => 'Al Ain', 'ae' => 'العين'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 3, 'name' => json_encode(['en' => 'Al Shamkha', 'ae' => 'الشمخة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 4, 'name' => json_encode(['en' => 'Al Shahama', 'ae' => 'الشهامة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 5, 'name' => json_encode(['en' => 'Al Falah', 'ae' => 'الفلاح'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 6, 'name' => json_encode(['en' => 'Baniyas', 'ae' => 'بني ياس'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 7, 'name' => json_encode(['en' => 'Sweihan', 'ae' => 'سويحان'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 8, 'name' => json_encode(['en' => 'Al Wathba', 'ae' => 'الوثبة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 9, 'name' => json_encode(['en' => 'Al Taweelah', 'ae' => 'الطويلة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 10, 'name' => json_encode(['en' => 'Liwa Oasis', 'ae' => 'واحة ليوا'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 11, 'name' => json_encode(['en' => 'Musaffah', 'ae' => 'مصفح'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 12, 'name' => json_encode(['en' => 'Al Dhafra', 'ae' => 'الظفرة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 1],
            ['id' => 13, 'name' => json_encode(['en' => 'Al Bastakiya', 'ae' => 'البستكية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 14, 'name' => json_encode(['en' => 'Al Karama', 'ae' => 'الكرامة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 15, 'name' => json_encode(['en' => 'Bur Dubai', 'ae' => 'بر دبي'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 16, 'name' => json_encode(['en' => 'Business Bay', 'ae' => 'الخليج التجاري'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 17, 'name' => json_encode(['en' => 'Downtown Dubai', 'ae' => 'وسط مدينة دبي'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 18, 'name' =>json_encode(['en' => 'Dubai Marina', 'ae' => 'مرسى دبي'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 4],
            ['id' => 19, 'name' =>json_encode(['en' => 'Al Butina', 'ae' => 'البطينة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 20, 'name' =>json_encode(['en' => 'Al Gharayen', 'ae' => 'الغرايين'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 21, 'name' =>json_encode(['en' => 'Al Heera', 'ae' => 'الحيرة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 22, 'name' =>json_encode(['en' => 'Al Qasba', 'ae' => 'القصباء'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 23, 'name' =>json_encode(['en' => 'Al Riqa', 'ae' => 'الرقة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 24, 'name' =>json_encode(['en' => 'Al Sharq', 'ae' => 'الشرق'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 25, 'name' =>json_encode(['en' => 'Halwan', 'ae' => 'حلوان'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 26, 'name' =>json_encode(['en' => 'Industrial areas', 'ae' => 'المناطق الصناعية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 27, 'name' =>json_encode(['en' => 'Mughadir', 'ae' => 'مغادر'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 28, 'name' =>json_encode(['en' => 'Wasit', 'ae' => 'واسط'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 3],
            ['id' => 29, 'name' =>json_encode(['en' => 'Al Hamrah', 'ae' => 'الحمرة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 30, 'name' =>json_encode(['en' => 'Al Khor', 'ae' => 'الخور'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 31, 'name' =>json_encode(['en' => 'Al Maidan', 'ae' => 'الميدان'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 32, 'name' =>json_encode(['en' => 'Latain', 'ae' => 'لطين'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 33, 'name' =>json_encode(['en' => 'Defence Camp', 'ae' => 'معسكر الدفاع'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 34, 'name' =>json_encode(['en' => 'Industrial Area', 'ae' => 'المنطقة الصناعية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 35, 'name' =>json_encode(['en' => 'Old Town Area', 'ae' => 'منطقة المدينة القديمة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 36, 'name' =>json_encode(['en' => 'Al Ramlah', 'ae' => 'الرملة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 7],
            ['id' => 37, 'name' =>json_encode(['en' => 'Thoban', 'ae' => 'ثوبان'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 38, 'name' =>json_encode(['en' => 'Al Faseel', 'ae' => 'الفصيل'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 39, 'name' =>json_encode(['en' => 'Al Hayl', 'ae' => 'الحيل'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 40, 'name' =>json_encode(['en' => 'Dibba Al Fujairah', 'ae' => 'دبا الفجيرة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 41, 'name' =>json_encode(['en' => 'Corniche', 'ae' => 'الكورنيش'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 42, 'name' =>json_encode(['en' => 'Dhadna', 'ae' => 'ضدنة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 43, 'name' =>json_encode(['en' => 'Sakamkam', 'ae' => 'سكمكم'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 44, 'name' =>json_encode(['en' => 'Mirbah', 'ae' => 'مربح'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 5],
            ['id' => 45, 'name' =>json_encode(['en' => 'Oraibi', 'ae' => 'العريبي'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 46, 'name' =>json_encode(['en' => 'Al Nakheel', 'ae' => 'النخيل'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 47, 'name' =>json_encode(['en' => 'Al Hudayba', 'ae' => 'الحضيبة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 48, 'name' =>json_encode(['en' => 'Al Maereed', 'ae' => 'المعريض'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 49, 'name' =>json_encode(['en' => 'Al Maa\'moura', 'ae' => 'المعمورة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 50, 'name' =>json_encode(['en' => 'Golan and Shaa\'', 'ae' => 'جولان و شع'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 6],
            ['id' => 51, 'name' =>json_encode(['en' => 'Al Bustan', 'ae' => 'البستان'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 52, 'name' =>json_encode(['en' => 'Al Hamidiya', 'ae' => 'الحميدية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 53, 'name' =>json_encode(['en' => 'Al Jurf', 'ae' => 'الجرف'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 54, 'name' =>json_encode(['en' => 'Al Maemiah', 'ae' => 'المعمية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 55, 'name' =>json_encode(['en' => 'Musharif', 'ae' => 'مشرف'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 56, 'name' =>json_encode(['en' => 'Rumailah', 'ae' => 'الرميلة'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 57, 'name' =>json_encode(['en' => 'Al Nuaimia', 'ae' => 'النعيمية'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],
            ['id' => 58, 'name' =>json_encode(['en' => 'Muwaihat', 'ae' => 'المويحات'], JSON_UNESCAPED_UNICODE), 'emirate_id' => 2],

        ];



        // Inserting records without created_at and updated_at
        DB::table('areas')->insert($areas);
    }
}
