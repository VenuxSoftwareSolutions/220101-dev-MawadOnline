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
            ['element_id' => 'dashboard ',
            'title' => 'Dashboard Overview',
            'description' => 'Welcome to your e-Shop dashboard! This is the command center for your business on MawadOnline. Here, you’ll oversee your shop\'s performance, monitor sales, and fine-tune settings to ensure your e-shop runs like a dream.']
        );
        Tour::updateOrCreate(
            ['step_number' => 2],
            ['element_id' => 'products',
            'title' => 'Step 1: Manage Products',
            'description' => 'Welcome to product management! This is your creative space to add, edit, and manage your listings. Showcase your best to captivate buyers and keep your inventory fresh and up-to-date.']
        );
        Tour::updateOrCreate(
            ['step_number' => 3],
            ['element_id' => 'catalog',
            'title' => 'Step 2: Catalog Management',
            'description' => 'In catalog management, organize your products into neat categories and collections. This step is all about enhancing discoverability and simplifying the search for customers.']
        );
        Tour::updateOrCreate(
            ['step_number' => 4],
            ['element_id' => 'reviews',
            'title' => 'Step 3: Monitor Reviews',
            'description' => 'The reviews dashboard is where you get real feedback. Keep tabs on customer opinions, manage responses, and refine your offerings to boost your shop’s reputation.']
        );
        Tour::updateOrCreate(
            ['step_number' => 5],
            ['element_id' => 'stock',
            'title' => 'Step 4: Stock Management',
            'description' => 'Efficient stock management is crucial. Keep an eye on your inventory levels to prevent overselling and maintain customer satisfaction with accurate updates.']
        );
        Tour::updateOrCreate(
            ['step_number' => 6],
            ['element_id' => 'warehouses',
            'title' => 'Step 5: Warehouses',
            'description' => 'Your warehouses are the backbone of your shop. Here, you can strategize your inventory layout across various locations for the most effective stock management.']
        );
        Tour::updateOrCreate(
            ['step_number' => 7],
            ['element_id' => 'stock_details',
            'title' => 'Step 6: Stock Details',
            'description' => 'Dive into the details of your stock here. Get a clear view of quantities, variations, and restocking strategies to keep your inventory organized and current.']
        );
        Tour::updateOrCreate(
            ['step_number' => 8],
            ['element_id' => 'order',
            'title' => 'Step 7: Order Management',
            'description' => 'Order management keeps you in the loop with incoming orders. Track, process payments, and oversee order fulfillment to ensure smooth transactions and prompt deliveries.']
        );
        Tour::updateOrCreate(
            ['step_number' => 9],
            ['element_id' => 'packages',
            'title' => 'Step 8: Package Management',
            'description' => 'Package Management allows you to select the best subscription plan for your needs. Compare features, choose your plan, and manage your subscription to enhance your business operations.']
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
            'description' => 'Staff management is about building your team. Add, remove, or assign roles to staff members to help run your e-shop effectively and streamline your operations.']
        );
        Tour::updateOrCreate(
            ['step_number' => 11],
            ['element_id' => 'lease',
            'title' => 'Step 10: Lease Management',
            'description' => 'Manage your lease agreements here. Whether it\'s for eShop premises or equipment, staying organized and compliant with lease terms is key to smooth operations.']
        );
        Tour::updateOrCreate(
            ['step_number' => 12],
            ['element_id' => 'sales',
            'title' => 'Step 11: Sales Monitoring',
            'description' => 'This is your sales performance dashboard. Analyze trends, track revenue, and gain insights to make informed decisions that drive your business’s growth and success.']
        );
        Tour::updateOrCreate(
            ['step_number' => 13],
            ['element_id' => 'support_tickets',
            'title' => 'Step 12: Support Tickets',
            'description' => 'In this step, you\'ll handle customer inquiries, feedback, and support requests. Providing timely assistance is vital for resolving issues and maintaining customer satisfaction.']
        );
        Tour::updateOrCreate(
            ['step_number' => 14],
            ['element_id' => 'setting',
            'title' => 'Step 13: Account Settings',
            'description' => 'Finally, adjust your account settings and preferences. Tailor your dashboard experience to your unique needs, optimizing your workflow for peak efficiency.']
        );
        Tour::where('step_number', 15)->delete();

    }
}
