<?php

use Database\seeders\RoleSeeder;
use Illuminate\Database\Seeder;
use Database\seeds\EmirateSeeder;
use Database\seeds\CategorySeeder;
use Database\seeds\MigrationSeeder;
use Database\Seeders\PackagesSeeder;
use Database\seeds\AreasTableSeeder;
use Database\seeds\PermissionSeeder;
use Database\seeds\TranslationsTableSeeder;
use Database\seeds\AddSellerRoleToUserSeeder;
use Database\Seeders\CreateSellerAndShopSeeder;
use Database\seeds\TranslationsRegisterVendorPart2;
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
        // $this->call(MigrationSeeder::class);
    //     $this->call(EmirateSeeder::class);
     //    $this->call(AreasTableSeeder::class);
     //  $this->call(TranslationsTableSeeder::class);
        // $this->call(TranslationsTableSeeder::class);
      //  $this->call(TranslationsRegisterVendorPart2::class);
        //$this->call(CategorySeeder::class);
        $this->call(PermissionSeeder::class);
        //$this->call(RoleSeeder::class);
        $this->call(AddSellerRoleToUserSeeder::class);
        //$this->call(CreateSellerAndShopSeeder::class);
    }
}
