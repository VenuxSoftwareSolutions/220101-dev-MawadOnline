<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seller=Role::updateOrCreate(
            ['name' => 'seller'],
            ['guard_name' => 'web',
            'role_type' => '1',
            'created_by' => '1',
            'package_id' => '0',
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
