<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Staff;
use App\Models\SellerPackage;
use Illuminate\Database\Seeder;
use App\Models\SellerPackageTranslation;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SellerPackage::updateOrCreate(
            ['id' => '4'],
            ['name' => 'Pro',
            'amount' => '450']
        );
        SellerPackageTranslation::updateOrCreate(
            ['seller_package_id' => '4'],
            ['name' => 'Pro']
        );

        SellerPackage::updateOrCreate(
            ['id' => '5'],
            ['name' => 'Entreprise',
            'amount' => '0']
        );
        SellerPackageTranslation::updateOrCreate(
            ['seller_package_id' => '5'],
            ['name' => 'Entreprise']
        );

        Role::where('role_type', '1')->where('name', '!=', 'seller')->delete();

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_leases'],
            ['section' => 'seller_leases',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_sales'],
            ['section' => 'seller_sales',
            'guard_name' => 'web']
        );

        $pro=Role::updateOrCreate(
            ['name' => 'pro'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
            'package_id' => '4',
        ]);

        $pro->givePermissionTo('seller_show_product');
        $pro->givePermissionTo('seller_create_product');
        $pro->givePermissionTo('seller_edit_product');
        $pro->givePermissionTo('seller_view_product_reviews');
        $pro->givePermissionTo('seller_view_package_list');
        $pro->givePermissionTo('seller_view_all_packages');
        $pro->givePermissionTo('seller_view_all_orders');
        $pro->givePermissionTo('seller_view_order_details');
        $pro->givePermissionTo('seller_shop_settings');
        $pro->givePermissionTo('seller_view_support_tickets');
        $pro->givePermissionTo('seller_add_support_tickets');
        $pro->givePermissionTo('seller_show_support_tickets');
        $pro->givePermissionTo('seller_reply_support_tickets');
        $pro->givePermissionTo('seller_view_all_staffs');
        $pro->givePermissionTo('seller_add_staff');
        $pro->givePermissionTo('seller_edit_staff');
        $pro->givePermissionTo('seller_delete_staff');
        $pro->givePermissionTo('seller_add_inventory');
        $pro->givePermissionTo('seller_edit_or_remove_inventory');
        $pro->givePermissionTo('seller_inventory_history');
        $pro->givePermissionTo('seller_view_all_leases');
        $pro->givePermissionTo('seller_view_all_sales');

        // Step 1: Get users with the "seller" role
        $sellerRoleId = Role::where('name', 'seller')->value('id');
        $usersWithSellerRole = User::where('user_type','seller')->whereColumn('id','owner_id')->get();


        $sellers=User::where('user_type','seller')->whereColumn('id','!=','owner_id')->get();
        foreach($sellers as $seller){
            $staff=Staff::where('user_id',$seller->id)->first();
            if($staff){
                $staff->delete();
            }
            $seller->banned=4;
            $seller->save();
        }

        // Step 2: Change their role to "pro"
        $proRoleId = Role::where('name', 'pro')->first();
        foreach ($usersWithSellerRole as $user) {
            if($staff=Staff::where('user_id',$user->id)->first()){
                $staff->role_id = $pro->id;
                $staff->save();
            }else{
                $staff=new Staff;
                $staff->role_id = $pro->id;
                $staff->user_id = $user->id;
                $staff->save();
            }

            $user->syncRoles($pro->name);
        }



        $sales=Role::updateOrCreate(
            ['name' => 'Sales'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
        ]);
        $management=Role::updateOrCreate(
            ['name' => ' Inventory Management'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
        ]);
        $accounting=Role::updateOrCreate(
            ['name' => 'Accounting'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
        ]);
        $marketing=Role::updateOrCreate(
            ['name' => 'Marketing'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
        ]);



        $sales->givePermissionTo('seller_show_product');
        $sales->givePermissionTo('seller_create_product');
        $sales->givePermissionTo('seller_edit_product');
        $sales->givePermissionTo('seller_view_product_reviews');
        $sales->givePermissionTo('seller_view_all_orders');
        $sales->givePermissionTo('seller_view_order_details');
        $management->givePermissionTo('seller_add_inventory');
        $management->givePermissionTo('seller_edit_or_remove_inventory');
        $management->givePermissionTo('seller_inventory_history');
        $accounting->givePermissionTo('seller_view_all_leases');
        $accounting->givePermissionTo('seller_view_all_sales');

    }
}
