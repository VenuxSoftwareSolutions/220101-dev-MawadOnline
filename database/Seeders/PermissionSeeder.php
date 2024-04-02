<?php

namespace Database\seeds;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Permission::updateOrCreate(
            ['name' => 'seller_show_product'],
            ['section' => 'seller_product_attribute',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_create_product'],
            ['section' => 'seller_product_attribute',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_product'],
            ['section' => 'seller_product_attribute',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_product_bulk_import'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_product_bulk_export'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_show_digital_products'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_digital_product'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_digital_product'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_delete_digital_product'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_download_digital_product'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_product_reviews'],
            ['section' => 'seller_product',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_package_list'],
            ['section' => 'seller_package',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_packages'],
            ['section' => 'seller_package',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_orders'],
            ['section' => 'seller_orders',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_order_details'],
            ['section' => 'seller_orders',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_update_delivery_status'],
            ['section' => 'seller_orders',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_update_payment_status'],
            ['section' => 'seller_orders',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_refund_request'],
            ['section' => 'seller_refund_request',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_refund_request_approval'],
            ['section' => 'seller_refund_request',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_reject_refund_request'],
            ['section' => 'seller_refund_request',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_refund_request_reason'],
            ['section' => 'seller_refund_request',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_shop_settings'],
            ['section' => 'seller_shop_setting',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_shop_payment_history'],
            ['section' => 'seller_payment_history',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_withdraw_requests'],
            ['section' => 'seller_money_withdraw',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_money_withdraw_request'],
            ['section' => 'seller_money_withdraw',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_commission_history'],
            ['section' => 'seller_commission_history',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_support_tickets'],
            ['section' => 'seller_support_tickets',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_support_tickets'],
            ['section' => 'seller_support_tickets',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_show_support_tickets'],
            ['section' => 'seller_support_tickets',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_reply_support_tickets'],
            ['section' => 'seller_support_tickets',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_staff_roles'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_staff_role'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_staff_role'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_delete_staff_role'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_staffs'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_staff'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_staff'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_delete_staff'],
            ['section' => 'seller_staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate([
            'id' => '255'
            ,'name' => 'seller_view_conversations'],
            ['section' => 'seller_conversations',

            'guard_name' => 'web']);

        Permission::updateOrCreate(
            ['name' => 'view_seller_staff_roles'],
            ['section' => 'staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'add_seller_staff_role'],
            ['section' => 'staff',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_inventory'],
            ['section' => 'seller_inventory',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_or_remove_inventory'],
            ['section' => 'seller_inventory',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_inventory_history'],
            ['section' => 'seller_inventory',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_coupons'],
            ['section' => 'seller_coupons',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_coupon'],
            ['section' => 'seller_coupons',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_coupon'],
            ['section' => 'seller_coupons',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_delete_coupon'],
            ['section' => 'seller_coupons',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_all_wholesale_products'],
            ['section' => 'seller_wholesale_products',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_add_wholesale_product'],
            ['section' => 'seller_wholesale_products',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_edit_wholesale_product'],
            ['section' => 'seller_wholesale_products',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_delete_wholesale_product'],
            ['section' => 'seller_wholesale_products',
            'guard_name' => 'web']
        );

        Permission::updateOrCreate(
            ['name' => 'seller_view_product_query'],
            ['section' => 'seller_product_query',
            'guard_name' => 'web']
        );

        $seller=Role::updateOrCreate(
            ['name' => 'seller'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '9',
        ]);
        $seller->givePermissionTo('seller_show_product');
        $seller->givePermissionTo('seller_create_product');
        $seller->givePermissionTo('seller_edit_product');
        $seller->givePermissionTo('seller_product_bulk_import');
        $seller->givePermissionTo('seller_product_bulk_export');
        $seller->givePermissionTo('seller_show_digital_products');
        $seller->givePermissionTo('seller_add_digital_product');
        $seller->givePermissionTo('seller_edit_digital_product');
        $seller->givePermissionTo('seller_delete_digital_product');
        $seller->givePermissionTo('seller_download_digital_product');
        $seller->givePermissionTo('seller_view_product_reviews');
        $seller->givePermissionTo('seller_view_package_list');
        $seller->givePermissionTo('seller_view_all_packages');
        $seller->givePermissionTo('seller_view_all_orders');
        $seller->givePermissionTo('seller_view_order_details');
        $seller->givePermissionTo('seller_update_delivery_status');
        $seller->givePermissionTo('seller_update_payment_status');
        $seller->givePermissionTo('seller_view_all_refund_request');
        $seller->givePermissionTo('seller_refund_request_approval');
        $seller->givePermissionTo('seller_reject_refund_request');
        $seller->givePermissionTo('seller_view_refund_request_reason');
        $seller->givePermissionTo('seller_shop_settings');
        $seller->givePermissionTo('seller_shop_payment_history');
        $seller->givePermissionTo('seller_view_withdraw_requests');
        $seller->givePermissionTo('seller_money_withdraw_request');
        $seller->givePermissionTo('seller_view_commission_history');
        $seller->givePermissionTo('seller_view_support_tickets');
        $seller->givePermissionTo('seller_add_support_tickets');
        $seller->givePermissionTo('seller_show_support_tickets');
        $seller->givePermissionTo('seller_reply_support_tickets');
        $seller->givePermissionTo('seller_view_staff_roles');
        $seller->givePermissionTo('seller_add_staff_role');
        $seller->givePermissionTo('seller_edit_staff_role');
        $seller->givePermissionTo('seller_delete_staff_role');
        $seller->givePermissionTo('seller_view_all_staffs');
        $seller->givePermissionTo('seller_add_staff');
        $seller->givePermissionTo('seller_edit_staff');
        $seller->givePermissionTo('seller_delete_staff');
        $seller->givePermissionTo('seller_view_conversations');
        $seller->givePermissionTo('seller_add_inventory');
        $seller->givePermissionTo('seller_edit_or_remove_inventory');
        $seller->givePermissionTo('seller_inventory_history');
        $seller->givePermissionTo('seller_view_all_coupons');
        $seller->givePermissionTo('seller_add_coupon');
        $seller->givePermissionTo('seller_edit_coupon');
        $seller->givePermissionTo('seller_delete_coupon');
        $seller->givePermissionTo('seller_view_all_wholesale_products');
        $seller->givePermissionTo('seller_add_wholesale_product');
        $seller->givePermissionTo('seller_edit_wholesale_product');
        $seller->givePermissionTo('seller_delete_wholesale_product');
        $seller->givePermissionTo('seller_view_product_query');

        $admin=Role::where('name','Super Admin')->first();
        $admin->givePermissionTo('view_seller_staff_roles');
        $admin->givePermissionTo('add_seller_staff_role');

    }
}
