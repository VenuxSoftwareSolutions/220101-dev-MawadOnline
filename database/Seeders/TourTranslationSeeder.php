<?php

namespace Database\Seeders;

use App\Models\TourTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TourTranslationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $steps = [
            1 => [
                'en' => ['title' => 'Dashboard Overview','description' => 'Welcome to your e-Shop dashboard! This is the command center for your business on MawadOnline. Here, you’ll oversee your shop\'s performance, monitor sales, and fine-tune settings to ensure your e-shop runs like a dream.'],
                'ae' => ['title' => 'مقدمة', 'description' => 'مقدمة الجولة']
            ],
            2 => [
                'en' => ['title' => 'Step 1: Manage Products','description' => 'Welcome to product management! This is your creative space to add, edit, and manage your listings. Showcase your best to captivate buyers and keep your inventory fresh and up-to-date.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            3 => [
                'en' => ['title' => 'Step 2: Catalog Management', 'description' => 'In catalog management, organize your products into neat categories and collections. This step is all about enhancing discoverability and simplifying the search for customers.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            4 => [
                'en' => ['title' => 'Step 3: Monitor Reviews', 'description' => 'The reviews dashboard is where you get real feedback. Keep tabs on customer opinions, manage responses, and refine your offerings to boost your shop’s reputation.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            5 => [
                'en' => ['title' => 'Step 4: Stock Management', 'description' => 'Efficient stock management is crucial. Keep an eye on your inventory levels to prevent overselling and maintain customer satisfaction with accurate updates.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            6 => [
                'en' => ['title' => 'Step 5: Warehouses', 'description' => 'Your warehouses are the backbone of your shop. Here, you can strategize your inventory layout across various locations for the most effective stock management.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            7 => [
                'en' => ['title' => 'Step 6: Stock Details', 'description' => 'Dive into the details of your stock here. Get a clear view of quantities, variations, and restocking strategies to keep your inventory organized and current.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            8 => [
                'en' => ['title' => 'Step 7: Order Management', 'description' => 'Order management keeps you in the loop with incoming orders. Track, process payments, and oversee order fulfillment to ensure smooth transactions and prompt deliveries.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            9 => [
                'en' => ['title' => 'Step 8: Package Management', 'description' => 'In package management, choose the right materials and methods to ensure your products are well-protected and presented beautifully as they travel to customers.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            10 => [
                'en' => ['title' => 'Step 9: Staff Management', 'description' => 'Staff management is about building your team. Add, remove, or assign roles to staff members to help run your e-shop effectively and streamline your operations.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            11 => [
                'en' => ['title' => 'Step 10: Lease Management', 'description' => 'Manage your lease agreements here. Whether it\'s for eShop premises or equipment, staying organized and compliant with lease terms is key to smooth operations.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            12 => [
                'en' => ['title' => 'Step 11: Sales Monitoring', 'description' => 'This is your sales performance dashboard. Analyze trends, track revenue, and gain insights to make informed decisions that drive your business’s growth and success.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            13 => [
                'en' => ['title' => 'Step 12: Support Tickets', 'description' => 'In this step, you\'ll handle customer inquiries, feedback, and support requests. Providing timely assistance is vital for resolving issues and maintaining customer satisfaction.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            14 => [
                'en' => ['title' => 'Step 13: Account Settings', 'description' => 'Finally, adjust your account settings and preferences. Tailor your dashboard experience to your unique needs, optimizing your workflow for peak efficiency.'],
                'ae' => ['title' => 'المرحلة 1', 'description' => 'وصف المرحلة 1']
            ],
            // Add similar entries for steps 3 to 14
        ];

        foreach ($steps as $step_number => $translations) {
            $step = Tour::where('step_number', $step_number)->first();

            foreach ($translations as $lang => $data) {
                $step_translation = TourTranslation::firstOrNew(['lang' => $lang, 'tour_id' => $step->id]);
                $step_translation->title = $data['title'];
                $step_translation->description = $data['description'];
                $step_translation->save();
            }
        }

        // $step_1 = Tour::where('step_number',1)->first();

        // $step_1_translation = TourTranslation::firstOrNew(['lang' =>'en', 'tour_id' => $step_1->id]);
        // $step_1_translation->title = 'Sales';
        // $step_1_translation->description = 'Sales';
        // $step_1_translation->save();

        // $step_1_translation_ae = TourTranslation::firstOrNew(['lang' =>'ae', 'tour_id' => $step_1->id]);
        // $step_1_translation_ae->title = 'Sales';
        // $step_1_translation_ae->description = 'Sales';
        // $step_1_translation_ae->save();
    }
}
