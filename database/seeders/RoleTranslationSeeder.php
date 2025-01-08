<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $salesRole = Role::where('name', 'Sales')->first();
        $inventoryRole = Role::where('name', ' Inventory Management')->first();
        $accountingRole = Role::where('name', 'Accounting')->first();
        $marketingRole = Role::where('name', 'Marketing')->first();

        $salesRole_translation = RoleTranslation::firstOrNew(['lang' =>'en', 'role_id' => $salesRole->id]);
        $salesRole_translation->name = 'Sales';
        $salesRole_translation->save();

        $salesRole_translationAe = RoleTranslation::firstOrNew(['lang' =>'ae', 'role_id' => $salesRole->id]);
        $salesRole_translationAe->name = 'المبيعات';
        $salesRole_translationAe->save();

        $inventoryRole_translation = RoleTranslation::firstOrNew(['lang' => 'en', 'role_id' => $inventoryRole->id]);
        $inventoryRole_translation->name = 'Inventory Management';
        $inventoryRole_translation->save();

        $inventoryRole_translationAe = RoleTranslation::firstOrNew(['lang' => 'ae', 'role_id' => $inventoryRole->id]);
        $inventoryRole_translationAe->name = 'إدارة المخزون';
        $inventoryRole_translationAe->save();

        $accountingRole_translation = RoleTranslation::firstOrNew(['lang' => 'en', 'role_id' => $accountingRole->id]);
        $accountingRole_translation->name = 'Accounting';
        $accountingRole_translation->save();

        $accountingRole_translationAe = RoleTranslation::firstOrNew(['lang' => 'ae', 'role_id' => $accountingRole->id]);
        $accountingRole_translationAe->name = 'المحاسبة';
        $accountingRole_translationAe->save();

        $marketingRole_translation = RoleTranslation::firstOrNew(['lang' => 'en', 'role_id' => $marketingRole->id]);
        $marketingRole_translation->name = 'Marketing';
        $marketingRole_translation->save();

        $marketingRole_translationAe = RoleTranslation::firstOrNew(['lang' => 'ae', 'role_id' => $marketingRole->id]);
        $marketingRole_translationAe->name = 'التسويق';
        $marketingRole_translationAe->save();
    }
}
