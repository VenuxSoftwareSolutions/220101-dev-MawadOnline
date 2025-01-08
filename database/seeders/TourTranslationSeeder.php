<?php

namespace Database\Seeders;

use App\Models\Tour;
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
                'ae' => ['title' => 'نظرة عامة على لوحة التحكم', 'description' => 'مرحباً بك في لوحة تحكم متجرك الإلكتروني! هذا هو مركز القيادة لمتجرك على مواد أونلاين. هنا ستتمكن من الإشراف على أداء متجرك، ومراقبة المبيعات، وضبط الإعدادات لضمان عمل متجرك الإلكتروني بشكل رائع.']
            ],
            2 => [
                'en' => ['title' => 'Step 1: Manage Products','description' => 'Welcome to product management! This is your creative space to add, edit, and manage your listings. Showcase your best to captivate buyers and keep your inventory fresh and up-to-date.'],
                'ae' => ['title' => 'الخطوة 1: إدارة المنتجات', 'description' => 'مرحباً بك في قسم إدارة المنتجات. هذه هي مساحتك الإبداعية لإضافة وتعديل وإدارة قوائمك. إعرض كُل ما لديك لجذب الشراءين وحافظ على مخزونك مُحدَّثا.']
            ],
            3 => [
                'en' => ['title' => 'Step 2: Catalog Management', 'description' => 'In catalog management, organize your products into neat categories and collections. This step is all about enhancing discoverability and simplifying the search for customers.'],
                'ae' => ['title' => 'الخطوة 2: إدارة الكتالوج', 'description' => 'ي قسم إدارة الكتالوج، قم بتنظيم منتجاتك في فئات ومجموعات مرتبة. تهدف هذه الخطوة إلى تحسين إمكانية إكتشاف بضائعك وتبسيط البحث للزبائن']
            ],
            4 => [
                'en' => ['title' => 'Step 3: Monitor Reviews', 'description' => 'The reviews dashboard is where you get real feedback. Keep tabs on customer opinions, manage responses, and refine your offerings to boost your shop’s reputation.'],
                'ae' => ['title' => 'الخطوة 3: مراقبة المراجعات', 'description' => 'قسم المراجعات هو المكان الذي تقرأ فيه تعليقات عملاءك الحقيقية. تابع آراءهم، و تواصل معهم، وحسِّن أداءك لتعزيز سمعة متجرك.']
            ],
            5 => [
                'en' => ['title' => 'Step 4: Stock Management', 'description' => 'Efficient stock management is crucial. Keep an eye on your inventory levels to prevent overselling and maintain customer satisfaction with accurate updates.'],
                'ae' => ['title' => 'الخطوة 4: إدارة المخزون', 'description' => 'إدارة المخزون بكفاءة خو أمر بالغ الأهمية. تابع مخزونك، لتتجنب البيع من غير مخزون، حفاظاً على رضا عملاءك وسمعة متجرك. حدِّث مخزونك على الدوام.']
            ],
            6 => [
                'en' => ['title' => 'Step 5: Warehouses', 'description' => 'Your warehouses are the backbone of your shop. Here, you can strategize your inventory layout across various locations for the most effective stock management.'],
                'ae' => ['title' => 'الخطوة 5: المستودعات ', 'description' => 'المستودعات هي العمود الفقري لمتجرك. هنا، يمكنك إدارة مستودعاتك المختلفة بطريقة إستراتيجية كما هي في الواقع، بطريقة أكثر فعالية تُلبي طلب عملائك.']
            ],
            7 => [
                'en' => ['title' => 'Step 6: Stock Details', 'description' => 'Dive into the details of your stock here. Get a clear view of quantities, variations, and restocking strategies to keep your inventory organized and current.'],
                'ae' => ['title' => 'الخطوة 6: تفاصيل المخزون ', 'description' => 'هنا تقبع تفاصيل مخزونك من المنتجات. راجع مخزونك من منتجاتك، الكميات المتوفرة، الطرازات المتوفرة، وكل ما هو متعلق بمنتجاتك، بهدف تنظيم مخزونك وتحديثه.']
            ],
            8 => [
                'en' => ['title' => 'Step 7: Order Management', 'description' => 'Order management keeps you in the loop with incoming orders. Track, process payments, and oversee order fulfillment to ensure smooth transactions and prompt deliveries.'],
                'ae' => ['title' => 'الخطوة 7: إدارة الطلبات ', 'description' => 'إدارة الطلبات تبقيك على إطلاع دائم بالطلبات الواردة. تتبع الطلبات، عالج المدفوعات، وأشرف على تنفيذ الطلبات لضمان معاملات سلسة وتسليم فوري للطلبات.']
            ],
            9 => [
                'en' => ['title' => 'Step 8: Package Management', 'description' => 'Package Management allows you to select the best subscription plan for your needs. Compare features, choose your plan, and manage your subscription to enhance your business operations.'],
                'ae' => ['title' => 'الخطوة 8: إدارة الباقات', 'description' => 'تتيح لك إدارة الباقات اختيار أفضل خطة اشتراك تناسب احتياجاتك. قارن بين الميزات، واختر خطتك، وقم بإدارة اشتراكك لتحسين عملياتك التجارية.']
            ],
            10 => [
                'en' => ['title' => 'Step 9: Staff Management', 'description' => 'Staff management is about building your team. Add, remove, or assign roles to staff members to help run your e-shop effectively and streamline your operations.'],
                'ae' => ['title' => 'الخطوة 9: إدارة الموظفين ', 'description' => 'إدارة الموظفين تتعلق ببناء فريقك. أضف، عيِّن، أو حتى أزِل، أدوار لأعضاء فريقك، لمساعدتك في إدارة وتشغيل متجرك الإلكتروني بكفاءة.']
            ],
            11 => [
                'en' => ['title' => 'Step 10: eShop Lease Management ', 'description' => 'Manage your lease agreements here. Whether it\'s for eShop premises or equipment, staying organized and compliant with lease terms is key to smooth operations.'],
                'ae' => ['title' => 'الخطوة 10: إدارة عقود إيجار متجرك الإلكتروني ', 'description' => 'قم بإدارة اتفاقيات الإيجار الخاصة بمتجرك الإلكتروني هنا. الحفاظ على النظام والإمتثال لشروط الإيجار هو المفتاح للعمل السلس.']
            ],
            12 => [
                'en' => ['title' => 'Step 11: Sales Monitoring', 'description' => 'This is your sales performance dashboard. Analyze trends, track revenue, and gain insights to make informed decisions that drive your business’s growth and success.'],
                'ae' => ['title' => 'الخطوة 11: مراقبة المبيعات ', 'description' => 'هذه هي لوحة التحكم الخاصة بأداء مبيعاتك. قم بتحليل مبيعاتك، وتتبع الإيرادات، واكتسب رؤى لاتخاذ قرارات مدروسة تدفع بنمو ونجاح عملك.']
            ],
            13 => [
                'en' => ['title' => 'Step 12: Support Tickets', 'description' => 'In this step, you\'ll handle customer inquiries, feedback, and support requests. Providing timely assistance is vital for resolving issues and maintaining customer satisfaction.'],
                'ae' => ['title' => 'الخطوة 12: تذاكر الدعم ', 'description' => 'في هذه الخطوة، ستتعامل مع استفسارات العملاء، وملاحظاتهم، وطلبات الدعم. تقديم المساعدة في الوقت المناسب أمر حيوي لحل المشكلات والحفاظ على رضا العملاء.']
            ],
            14 => [
                'en' => ['title' => 'Step 13: Account Settings', 'description' => 'Finally, adjust your account settings and preferences. Tailor your dashboard experience to your unique needs, optimizing your workflow for peak efficiency.'],
                'ae' => ['title' => 'الخطوة 13: إعدادات الحساب ', 'description' => 'أخيرًا، قم بتعديل إعدادات حسابك وتفضيلاتك. صمم تجربة لوحة التحكم وفقًا لاحتياجاتك الخاصة، محسنًا سير العمل لتحقيق أعلى كفاءة ممكنة']
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
