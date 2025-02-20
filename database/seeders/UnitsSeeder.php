<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Seeder;

class UnitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sqlFilePath = database_path('data/units_inserts.sql');

        if (File::exists($sqlFilePath)) {
            $sql = File::get($sqlFilePath);

            DB::unprepared($sql);

            $this->command->info('SQL file seeded successfully!');
        } else {
            $this->command->error('SQL file not found: ' . $sqlFilePath);
        }
    }
}
