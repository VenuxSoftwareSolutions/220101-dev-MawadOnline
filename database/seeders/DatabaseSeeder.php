<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
/* use Database\Seeders\ProductSeeder; */
use Database\Seeders\UnitsSeeder;

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
        // $this->call(EmirateSeeder::class);
        // $this->call(AreasTableSeeder::class);
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
        /* $this->call(TranslationProductSeeder::class); */
        /* $this->call(TranslationCatalogueSeeder::class); */
        /* $this->call(ProductSeeder::class); */
        //$this->call(UnitsSeeder::class);
        $this->call([
            BusinessSettingsTableSeeder::class,
        ]);
    
    }
}
