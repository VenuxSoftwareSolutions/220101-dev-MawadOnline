<?php

namespace Database\Seeders;
use App\Models\ColorGroup;
use App\Models\Color;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupColor extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groupColor = ColorGroup::create([
            'name' => 'Group 1',
        ]);

        $colors = Color::all();

        // Extraire les IDs des Color
        $colorIds = $colors->pluck('id')->toArray();

        // Synchroniser le GroupColor avec tous les Color
        $groupColor->colors()->sync($colorIds);
    }
}
