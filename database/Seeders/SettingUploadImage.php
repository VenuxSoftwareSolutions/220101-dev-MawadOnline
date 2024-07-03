<?php

namespace Database\Seeders;

use App\Models\BusinessSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingUploadImage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessSetting::updateOrCreate(
            ['type' => 'image_min_width'],
            ['value' => 1920
            ]
        );
        BusinessSetting::updateOrCreate(
            ['type' => 'image_img_quality'],
            ['value' => 80
            ]
        );
    }
}
