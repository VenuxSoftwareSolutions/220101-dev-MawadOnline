<?php

use Illuminate\Database\Seeder;
use Database\seeds\MigrationSeeder;
use Database\seeds\TranslateattributeSeeder;
use Database\seeds\AddPermessionEnablingAttributeSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(MigrationSeeder::class);
        $this->call(TranslateattributeSeeder::class);
        $this->call(AddPermessionEnablingAttributeSeeder::class);
    }
}
