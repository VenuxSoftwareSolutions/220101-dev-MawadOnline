<?php

namespace Database\Seeders;

use App\Models\SellerPackage;
use Illuminate\Database\Seeder;
use App\Models\SellerPackageTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackageTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $proPackage = SellerPackage::where('name', 'Pro')->first();
        $entreprisePackage = SellerPackage::where('name', 'Entreprise')->first();

        $proPackage_translation = SellerPackageTranslation::firstOrNew(['lang' =>'en', 'seller_package_id' => $proPackage->id]);
        $proPackage_translation->name = 'Pro';
        $proPackage_translation->save();

        $proPackage_translation_ae = SellerPackageTranslation::firstOrNew(['lang' =>'ae', 'seller_package_id' => $proPackage->id]);
        $proPackage_translation_ae->name = 'محترف';
        $proPackage_translation_ae->save();


        $entreprisePackage_translation = SellerPackageTranslation::firstOrNew(['lang' =>'en', 'seller_package_id' => $entreprisePackage->id]);
        $entreprisePackage_translation->name = 'Entreprise';
        $entreprisePackage_translation->save();

        $entreprisePackage_translation_ae = SellerPackageTranslation::firstOrNew(['lang' =>'ae', 'seller_package_id' => $entreprisePackage->id]);
        $entreprisePackage_translation_ae->name = 'مؤسسة';
        $entreprisePackage_translation_ae->save();
    }
}
