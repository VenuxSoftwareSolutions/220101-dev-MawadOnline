<?php

namespace Database\seeds;

use Illuminate\Database\Seeder;
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
        Permission::create([
            'id' => '217',
            'name' => 'seller_show_product',
            'section' => 'seller_product_attribute',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '218',
            'name' => 'seller_create_product',
            'section' => 'seller_product_attribute',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '219',
            'name' => 'seller_edit_product',
            'section' => 'seller_product_attribute',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '221',
            'name' => 'seller_product_bulk_import',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '222',
            'name' => 'seller_product_bulk_export',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '223',
            'name' => 'seller_show_digital_products',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '224',
            'name' => 'seller_add_digital_product',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '225',
            'name' => 'seller_edit_digital_product',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '226',
            'name' => 'seller_delete_digital_product',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '227',
            'name' => 'seller_download_digital_product',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '228',
            'name' => 'seller_view_product_reviews',
            'section' => 'seller_product',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '229',
            'name' => 'seller_view_package_list',
            'section' => 'seller_package',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '230',
            'name' => 'seller_view_all_packages',
            'section' => 'seller_package',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '231',
            'name' => 'seller_view_all_orders',
            'section' => 'seller_orders',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '232',
            'name' => 'seller_view_order_details',
            'section' => 'seller_orders',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '233',
            'name' => 'seller_update_delivery_status',
            'section' => 'seller_orders',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '234',
            'name' => 'seller_update_payment_status',
            'section' => 'seller_orders',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '235',
            'name' => 'seller_view_all_refund_request',
            'section' => 'seller_refund_request',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '236',
            'name' => 'seller_refund_request_approval',
            'section' => 'seller_refund_request',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '237',
            'name' => 'seller_reject_refund_request',
            'section' => 'seller_refund_request',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '238',
            'name' => 'seller_view_refund_request_reason',
            'section' => 'seller_refund_request',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '239',
            'name' => 'seller_shop_settings',
            'section' => 'seller_shop_setting',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '220',
            'name' => 'seller_shop_payment_history',
            'section' => 'seller_payment_history',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '240',
            'name' => 'seller_view_withdraw_requests',
            'section' => 'seller_money_withdraw',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '241',
            'name' => 'seller_money_withdraw_request',
            'section' => 'seller_money_withdraw',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '242',
            'name' => 'seller_view_commission_history',
            'section' => 'seller_commission_history',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '243',
            'name' => 'seller_view_support_tickets',
            'section' => 'seller_support_tickets',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '244',
            'name' => 'seller_add_support_tickets',
            'section' => 'seller_support_tickets',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '245',
            'name' => 'seller_show_support_tickets',
            'section' => 'seller_support_tickets',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '246',
            'name' => 'seller_reply_support_tickets',
            'section' => 'seller_support_tickets',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '247',
            'name' => 'seller_view_staff_roles',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '248',
            'name' => 'seller_add_staff_role',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '249',
            'name' => 'seller_edit_staff_role',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '250',
            'name' => 'seller_delete_staff_role',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '251',
            'name' => 'seller_view_all_staffs',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '252',
            'name' => 'seller_add_staff',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '253',
            'name' => 'seller_edit_staff',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '254',
            'name' => 'seller_delete_staff',
            'section' => 'seller_staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '255',
            'name' => 'seller_view_conversations',
            'section' => 'seller_conversations',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '256',
            'name' => 'view_seller_staff_roles',
            'section' => 'staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '257',
            'name' => 'add_seller_staff_role',
            'section' => 'staff',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '258',
            'name' => 'seller_add_inventory',
            'section' => 'seller_inventory',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '259',
            'name' => 'seller_edit_or_remove_inventory',
            'section' => 'seller_inventory',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '260',
            'name' => 'seller_inventory_history',
            'section' => 'seller_inventory',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '261',
            'name' => 'seller_view_all_coupons',
            'section' => 'seller_coupons',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '262',
            'name' => 'seller_add_coupon',
            'section' => 'seller_coupons',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '263',
            'name' => 'seller_edit_coupon',
            'section' => 'seller_coupons',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '264',
            'name' => 'seller_delete_coupon',
            'section' => 'seller_coupons',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '265',
            'name' => 'seller_view_all_wholesale_products',
            'section' => 'seller_wholesale_products',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '266',
            'name' => 'seller_add_wholesale_product',
            'section' => 'seller_wholesale_products',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '267',
            'name' => 'seller_edit_wholesale_product',
            'section' => 'seller_wholesale_products',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '268',
            'name' => 'seller_delete_wholesale_product',
            'section' => 'seller_wholesale_products',
            'guard_name' => 'web'
        ]);
        Permission::create([
            'id' => '269',
            'name' => 'seller_view_product_query',
            'section' => 'seller_product_query',
            'guard_name' => 'web'
        ]);

    }
}
