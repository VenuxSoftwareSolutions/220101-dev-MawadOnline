<?php

namespace Database\Seeders;

use App\Models\Tour;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tour::updateOrCreate(
            ['step_number' => 1],
            ['element_id' => 'dashboard',
            'title' => 'Dashboard',
            'description' => 'Welcome to your e-Shop dashboard! This is your central hub for managing your shop\'s performance, sales, and settings.']
        );
        Tour::updateOrCreate(
            ['step_number' => 2],
            ['element_id' => 'products',
            'title' => 'Step 1: Manage Products',
            'description' => 'Here, you can add, edit, and manage your products. Showcase your offerings to attract buyers and keep your inventory up to date.']
        );
        Tour::updateOrCreate(
            ['step_number' => 3],
            ['element_id' => 'catalog',
            'title' => 'Step 2: Catalog Management',
            'description' => 'Organize your products into categories and collections. Enhance discoverability and make it easier for customers to find what they\'re looking for.']
        );
        Tour::updateOrCreate(
            ['step_number' => 4],
            ['element_id' => 'reviews',
            'title' => 'Step 3: Monitor Reviews',
            'description' => 'Stay informed about what customers are saying. Manage and respond to reviews to maintain a positive reputation and improve your products.']
        );
        Tour::updateOrCreate(
            ['step_number' => 5],
            ['element_id' => 'stock',
            'title' => 'Step 4: Stock Management',
            'description' => 'Track your inventory levels and manage stock efficiently. Avoid overselling and keep your customers satisfied with accurate stock updates.']
        );
        Tour::updateOrCreate(
            ['step_number' => 6],
            ['element_id' => 'warehouses',
            'title' => 'Step 5: Warehouses',
            'description' => 'Manage warehouses where your stock is stored. Organize your inventory across different locations for efficient stock management.']
        );
        Tour::updateOrCreate(
            ['step_number' => 7],
            ['element_id' => 'stock_details',
            'title' => 'Step 6: Stock Details',
            'description' => 'View detailed information about your stock, including quantities, variations, and restocking options. Keep your inventory organized and up to date.']
        );
        Tour::updateOrCreate(
            ['step_number' => 8],
            ['element_id' => 'order',
            'title' => 'Step 7: Order Management',
            'description' => 'Keep track of incoming orders, process payments, and manage order fulfillment. Ensure smooth transactions and timely delivery to your customers.']
        );
        Tour::updateOrCreate(
            ['step_number' => 9],
            ['element_id' => 'packages',
            'title' => 'Step 8: Package Management',
            'description' => 'Manage packaging options and shipping details for your products. Choose the best packaging solutions to protect your items during transit.']
        );
        // Tour::updateOrCreate(
        //     ['step_number' => 10],
        //     ['element_id' => 'package_list',
        //     'title' => 'Step 9: Package List',
        //     'description' => 'View a list of all packages associated with your orders. Keep track of shipments and delivery status to provide accurate updates to customers.']
        // );
        Tour::updateOrCreate(
            ['step_number' => 10],
            ['element_id' => 'staff',
            'title' => 'Step 9: Staff Management',
            'description' => 'Add, remove, or manage staff members who assist with running your shop. Delegate tasks and collaborate effectively to streamline operations.']
        );
        Tour::updateOrCreate(
            ['step_number' => 11],
            ['element_id' => 'lease',
            'title' => 'Step 10: Lease Management',
            'description' => 'Manage lease agreements for your shop premises or equipment. Stay organized and ensure compliance with lease terms and conditions.']
        );
        Tour::updateOrCreate(
            ['step_number' => 12],
            ['element_id' => 'sales',
            'title' => 'Step 11: Sales',
            'description' => 'Monitor your sales performance, analyze trends, and track revenue. Gain insights into your business growth and make informed decisions to drive success.']
        );
        Tour::updateOrCreate(
            ['step_number' => 13],
            ['element_id' => 'support_tickets',
            'title' => 'Step 12: Support Tickets',
            'description' => 'Handle customer inquiries, feedback, and support requests. Provide timely assistance and resolve issues to maintain customer satisfaction.']
        );
        Tour::updateOrCreate(
            ['step_number' => 14],
            ['element_id' => 'setting',
            'title' => 'Step 13: Account Settings',
            'description' => 'Adjust your account settings and preferences. Customize your dashboard experience to suit your needs and optimize your workflow.']
        );
        Tour::where('step_number', 15)->delete();

    }
}
