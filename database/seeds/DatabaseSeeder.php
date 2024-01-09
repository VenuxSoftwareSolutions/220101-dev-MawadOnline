<?php

use Database\Seeders\AreasTableSeeder;
use Database\Seeders\EmirateSeeder;
use Database\Seeders\TranslationsRegisterVendorPart2;
use Database\Seeders\TranslationsTableSeeder;
use Database\seeds\AddPermessionEnablingAttributeSeeder;
use Database\seeds\CategorySeeder;
use Illuminate\Database\Seeder;
use Database\seeds\MigrationSeeder;


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
        // $this->call(MigrationSeeder::class);
    //     $this->call(EmirateSeeder::class);
     //    $this->call(AreasTableSeeder::class);
     //  $this->call(TranslationsTableSeeder::class);
        // $this->call(TranslationsTableSeeder::class);
        $this->call(TranslationsRegisterVendorPart2::class);

    //    $this->call(CategorySeeder::class);
    }
}
