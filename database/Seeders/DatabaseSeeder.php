<?php

use Database\Seeds\TourSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\RoleSeeder;
use Database\Seeders\LeaseSeeder;
use Database\Seeders\EmirateSeeder;
use Database\Seeders\CategorySeeder;
use Database\Seeders\PackagesSeeder;
use Database\Seeders\MigrationSeeder;
use Database\Seeders\AreasTableSeeder;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\TranslationsTableSeeder;
use Database\Seeders\AddSellerRoleToUserSeeder;
use Database\Seeders\CreateSellerAndShopSeeder;
use Database\Seeders\TranslationsRegisterVendorPart2;
use Database\Seeders\AddPermessionEnablingAttributeSeeder;
use Database\Seeders\TranslationProductSeeder;

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
        //$this->call(PermissionSeeder::class);
        //$this->call(RoleSeeder::class);
        //$this->call(AddSellerRoleToUserSeeder::class);
        //$this->call(CreateSellerAndShopSeeder::class);
        //$this->call(TourSeeder::class);
        //$this->call(LeaseSeeder::class);
        $this->call(TranslationProductSeeder::class);


    }
}
