<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'unit_of_sale',
            'lang_value' => 'وحدة البيع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'short_description',
            'lang_value' => 'وصف موجز',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'country_of_origin',
            'lang_value' => 'البلد الأصلي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'manufacturer',
            'lang_value' => 'الشركة المصنعة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'show_stock_quantity',
            'lang_value' => 'أظهر كمية المخزون',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'remaining_characters',
            'lang_value' => 'الحروف المتبقية:',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'product_media',
            'lang_value' => 'وسائط المنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_pricing_configuration',
            'lang_value' => 'إعدادات التسعير المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_product_pricing_configuration',
            'lang_value' => 'إعدادات التسعير المحددة مسبقًا للمنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'discount_percentage',
            'lang_value' => 'نسبة خصم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'discount_amount',
            'lang_value' => 'مقدار الخصم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'discountstartend',
            'lang_value' => 'الخصم (البداية/النهاية)',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'unit_price_vat_exclusive',
            'lang_value' => 'سعر الوحدة (حصري لضريبة القيمة المضافة)',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'from_qty',
            'lang_value' => 'من الكمية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'to_qty',
            'lang_value' => 'إلى الكمية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'percentage',
            'lang_value' => 'النسبة المئوية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'select_date',
            'lang_value' => 'اختر التاريخ',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'amount',
            'lang_value' => 'مبلغ',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_sample_pricing_configuration',
            'lang_value' => 'إعدادات التسعير المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'sample_description',
            'lang_value' => 'وصف العينة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'sample_price',
            'lang_value' => 'سعر العينة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_shipping_configuration',
            'lang_value' => 'إعدادات الشحن المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_product_shipping',
            'lang_value' => 'الشحن المحددة مسبقًا للمنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'mawadonline_3rd_party_shipping',
            'lang_value' => 'MawadOnline ثالث طرف شحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'activate_mawadonline_3rd_party_shipping',
            'lang_value' => 'تنشيط شحن الطرف الثالث MawadOnline',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'temperature_max',
            'lang_value' => 'درجة الحرارة القصوى',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'temperature_min',
            'lang_value' => 'درجة الحرارة الدنيا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'temperature_unit',
            'lang_value' => 'وحدة درجات الحرارة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'breakable',
            'lang_value' => 'قابلة للكسر',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'weight_unit',
            'lang_value' => 'وحدة الوزن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'package_weight',
            'lang_value' => 'وحدة الوزن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'height_cm',
            'lang_value' => 'الارتفاع (سم)',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'width_cm',
            'lang_value' => 'العرض (سم)',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'length_cm',
            'lang_value' => 'الطول (سم)',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'shipping_duration__charge',
            'lang_value' => 'مدة الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'charge_per_unit_of_sale',
            'lang_value' => 'الرسوم لكل وحدة بيع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'flatrate_amount',
            'lang_value' => 'المبلغ الموحد',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'flat_rate_amount',
            'lang_value' => 'المبلغ الموحد',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'charge_unit',
            'lang_value' => 'وحدة الرسوم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'choose_paid_by',
            'lang_value' => 'اختر من يدفع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'shipping_charge_type',
            'lang_value' => 'نوع رسوم الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'paid_by',
            'lang_value' => 'يدفعه',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_shipping_days',
            'lang_value' => 'أيام الشحن التقديرية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'أيام إعداد طلبات التقديرية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'shipper',
            'lang_value' => 'شاحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'flat',
            'lang_value' => 'ثابت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'kilograms',
            'lang_value' => 'كيلوغرام',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'pounds',
            'lang_value' => 'رطل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'celsius',
            'lang_value' => 'سيلسيوس',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'kelvin',
            'lang_value' => 'كلفن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'fahrenheit',
            'lang_value' => 'فهرنهايت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'choose_option',
            'lang_value' => 'اختر خيار',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'choose_type',
            'lang_value' => 'اختر النوع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'vendor',
            'lang_value' => 'بائع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'buyer',
            'lang_value' => 'مشتري',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'mawadonline_3rd_party_shippers',
            'lang_value' => 'شاحنو الطرف الثالث من mawadonline',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'choose_shipping_charge',
            'lang_value' => 'اختر رسوم الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'flatrate_regardless_of_quantity',
            'lang_value' => 'المعدل الثابت بغض النظر عن الكمية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'charging_per_unit_of_sale',
            'lang_value' => 'الشحن لكل وحدة بيع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'shippingby',
            'lang_value' => 'الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'estimated_sample_preparation_days',
            'lang_value' => 'الأيام المقدرة لتحضير العينة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'estimated_shipping_days',
            'lang_value' => 'أيام الشحن المقدرة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'paid_by',
            'lang_value' => 'مدفوع من قبل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'shipping_amount',
            'lang_value' => 'مبلغ الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'create_variants',
            'lang_value' => 'إنشاء متغيرات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'activate_variant_option',
            'lang_value' => 'تفعيل خيار المتغير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'variant_information',
            'lang_value' => 'معلومات المتغير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'draft_add_product',
            'lang_value' => 'مسودة إضافة منتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'draft',
            'lang_value' => 'مسودة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_pricing_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذا التسعير؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_documet_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذا المستند؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_documet_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذا المستند؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_picture_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذه الصورة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_variant_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذا المتغير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'are_you_sure_you_want_to_delete_this_shipping_',
            'lang_value' => 'هل أنت متأكد أنك تريد حذف هذا الشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'product_update',
            'lang_value' => 'تحديث المنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'quickly_access_and_organize_your_inventory_with_intuitive_tools_and_features_simplify_your________________________inventory_management_process_and_stay_in_control_of_your_products_effortlessly',
            'lang_value' => 'يمكنك الوصول إلى مخزونك وتنظيمه بسرعة باستخدام أدوات وميزات سهلة الاستخدام. قم بتبسيط عملية إدارة المخزون الخاصة بك وابقَ مسيطرًا على منتجاتك بكل سهولة.',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'cancel_update',
            'lang_value' => 'إلغاء التحديث',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'as_this_product_update_requires_approval_you_can_keep_the_last_approved_product_published_in_the_marketplace_until_the_update_is_approved_if_you_unpublish_the_last_approved_product_then_you_have_to_publish_the_updated_product_after_approval_manually_do_you_want_to_keep_the_last_approved_product_published',
            'lang_value' => 'نظرًا لأن تحديث هذا المنتج يتطلب الموافقة، يمكنك الاحتفاظ بالمنتج الأخير المعتمد منشورًا في السوق حتى يتم الموافقة على التحديث. إذا قمت بإلغاء نشر المنتج الأخير المعتمد، فعليك نشر المنتج المحدث بعد الموافقة يدويًا. هل تريد الاحتفاظ بالمنتج الأخير المعتمد منشورًا؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'something_went_wrong',
            'lang_value' => 'حدث خطأ ما',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'general_attributes',
            'lang_value' => 'السمات العامة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'variant_sku',
            'lang_value' => 'رمز المتغير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'variant_photos',
            'lang_value' => 'صور المتغير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'use_default_pricing_configuration',
            'lang_value' => 'استخدم إعدادات التسعيرالمحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'use_default_shipping',
            'lang_value' => 'استخدم الشحن المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'sample_available',
            'lang_value' => 'العينة متاحة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'use_default_sample_pricing_configuration',
            'lang_value' => 'استخدم اعدادات تسعير العينة المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'use_default_sample_shipping',
            'lang_value' => 'استخدم شحن العينة المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_sample_shipping',
            'lang_value' => 'شحن العينة المحددة مسبقًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'lowstock_warning',
            'lang_value' => 'تحذير النفاد من المخزون',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'create_variant',
            'lang_value' => 'إنشاء متغيّر',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'product_description_and_specifications',
            'lang_value' => 'وصف المنتج والمواصفات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'document',
            'lang_value' => 'مستند',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'document_name',
            'lang_value' => 'اسم المستند',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'variant_media',
            'lang_value' => 'وسائط المتغيرات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'save_as_draft',
            'lang_value' => 'حفظ كمسودة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'create_product',
            'lang_value' => 'إنشاء منتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_can_only_upload_a_maximum_of_10_files',
            'lang_value' => 'يمكنك تحميل ما يصل إلى 10 ملفات فقط',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_need_to_select_at_least_one_picture.',
            'lang_value' => 'تحتاج إلى تحديد صورة واحدة على الأقل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'pricing_configuration',
            'lang_value' => 'اعدادات التسعير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'ensure_that_the_difference_between_the_minimum_and_maximum_quantities_of_the_preceding_interval_must_be_equal_to_one',
            'lang_value' => 'تأكد من أن الفارق بين الحد الأدنى والحد الأقصى من النطاق السابق يجب أن يكون مساويًا لواحد.',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'overlap_found',
            'lang_value' => 'تم العثور على تداخل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'file_size_exceeds_15mb',
            'lang_value' => 'حجم الملف يتجاوز 15 ميغابايت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'total_file_size_exceeds_25mb_please_select_smaller_files',
            'lang_value' => 'إجمالي حجم الملفات يتجاوز 25 ميغابايت. يرجى تحديد ملفات أصغر حجماً',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'the_size_of_the_downloaded_document_',
            'lang_value' => 'حجم المستند المُحمّل :',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_dont_have_any_warehouse_supported_by_mawadonline_3rd_party_shippers_if_you_havent_created_your_warehouses_you_can_save_the_product_as_draft_create_your_warehouses_by_going_to_the_warehouses_page_under_inventory_management_and_then_you_may_continue_editing_your_product',
            'lang_value' => "ليس لديك أي مستودع مدعوم من قبل الشاحنين من جهة ثالثة عبر MawadOnline. إذا لم تقم بإنشاء مستودعاتك، يمكنك حفظ المنتج كمسودة، وإنشاء مستودعاتك بالذهاب إلى صفحة المستودعات تحت إدارة المخزون، ثم يمكنك متابعة تحرير منتجك.",
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'chargeable_weight__',
            'lang_value' => 'الوزن القابل للشحن',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'then_not_accepted_by_our_shipper',
            'lang_value' => 'ثم لم يتم قبوله من قبل الشاحن الخاص بنا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'chargeable_weight__0_then_accepted_by_our_shipper',
            'lang_value' => 'الوزن القابل للشحن = 0، ثم يتم قبوله من قبل الشاحنين الخاصين بنا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'then_accepted_by_aramex',
            'lang_value' => 'ثم تم قبوله من قبل أرامكس',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_cannot_selected_if_you_don',
            'lang_value' => 'لا يمكنك الاختيار إذا لم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 't_selected_vendor_in_shippers',
            'lang_value' => 'تقم باختيار البائع في الشاحنين',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_cannot_choose_shipping_charge_when_it_is_paid_by_vendor',
            'lang_value' => 'لا يمكنك اختيار رسوم الشحن عندما يدفعها البائع.',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'wrong_choice',
            'lang_value' => 'خيار خاطئ',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_ensure_that_all_required_fields_are_filled_to_know_all_information_about_your_package',
            'lang_value' => 'يرجى التأكد من ملء جميع الحقول المطلوبة لمعرفة كل المعلومات عن حزمتك',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'your_product_has_been_created_successfully_but_it_will_be_pending_for_admin_approval_you_can_set_the_product_published_to_appear_in_the_marketplace_once_approved_do_you_want_to_make_it_published',
            'lang_value' => 'تم إنشاء منتجك بنجاح، ولكنه سيكون في انتظار موافقة المسؤول. يمكنك تعيين نشر المنتج للظهور في السوق بمجرد الموافقة. هل تريد جعله منشورًا؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_can_create_the_inventory_of_the_products_and_make_it_ready_before_admin_approval_this_is_recommended_if_your_product_will_be_immediately_published_upon_approval_do_you_want_to_continue',
            'lang_value' => 'يمكنك إنشاء مخزون المنتجات وجعله جاهزًا قبل موافقة المسؤول. يُوصى بهذا إذا كان منتجك سيتم نشره فور الموافقة. هل ترغب في المتابعة؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_need_to_choose_at_least_one_attribute',
            'lang_value' => 'تحتاج إلى اختيار ميزة واحدة على الأقل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_check_your_pricing_configuration',
            'lang_value' => 'يرجى التحقق من اعدادات التسعير الخاص بك',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_select_a_category_without_subcategories',
            'lang_value' => 'يرجى اختيار فئة بدون فئات فرعية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'you_are_unable_to_enable_the_variant_option_because_the_selected_category_lacks_any_attributes',
            'lang_value' => 'لا يمكنك تمكين خيار المتغير لأن الفئة المحددة تفتقر إلى أي سمات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'select_a_category_before_activating_the_variant_option',
            'lang_value' => 'اختر فئة قبل تفعيل خيار المتغيّر',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_enter_a_product_name',
            'lang_value' => 'يرجى إدخال اسم المنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'maximum_10_photos_allowed',
            'lang_value' => 'الحد الأقصى المسموح به 10 صور',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'following_files_exceed_2mb_limit_',
            'lang_value' => 'الملفات التالية تتجاوز الحد البالغ 2 ميغابايت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'the_dimensions_of_the_images_have_exceeded_both_a_width_and_height_of_1280_pixels_',
            'lang_value' => 'أبعاد الصور قد تجاوزت كل من العرض والارتفاع 1280 بيكسل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'following_files_exceed_512ko_limit_',
            'lang_value' => 'الملفات التالية تتجاوز الحد البالغ 512 كيلوبايت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_upload_images_with_dimensions_between_300px_and_400px_for_both_width_and_height_',
            'lang_value' => 'يرجى تحميل الصور بأبعاد بين 300 بيكسل و 400 بيكسل لكل من العرض والارتفاع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'fill_all_required_fields_for_shippers_to_confirm_delivery_ability',
            'lang_value' => 'املأ جميع الحقول المطلوبة للشحنة لتأكيد قدرة التوصيل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'sku',
            'lang_value' => 'رمز المنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'products_management',
            'lang_value' => 'إدارة المنتجات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'mawad_catalogue',
            'lang_value' => 'قائمة Mawad',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'to_select_a_different_category_please_clear_the_search_field_however_you_must_choose_other_attributes_to_modify_your_variants',
            'lang_value' => 'لاختيار فئة مختلفة، يرجى مسح حقل البحث. ومع ذلك، يجب عليك اختيار سمات أخرى لتعديل المتغيرات الخاصة بك',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'select_brand',
            'lang_value' => 'اختر العلامة التجارية',
        ]);

        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'tags_input_cannot_be_empty',
            'lang_value' => 'لا يمكن ترك خانة العلامات فارغة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'thumbnail_images_will_be_generated_automatically_from_gallery_images_if_not_specified',
            'lang_value' => 'سيتم إنشاء صورة مصغرة تلقائياً من صور المعرض إذا لم يتم تحديدها',
        ]);

        DB::table('attribute_translations')
        ->where('lang', 'ar')
        ->update(['lang' => 'ae']);
    }
}
