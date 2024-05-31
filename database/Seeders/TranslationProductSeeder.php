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
            'lang_value' => 'تكوين التسعير الافتراضي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_product_pricing_configuration',
            'lang_value' => 'تكوين التسعير الافتراضي للمنتج',
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
            'lang_value' => '',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'amount',
            'lang_value' => 'مبلغ',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_sample_pricing_configuration',
            'lang_value' => 'تكوين التسعير الافتراضي للعينة',
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
            'lang_value' => 'تكوين الشحن الافتراضي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'default_product_shipping',
            'lang_value' => 'الشحن الافتراضي للمنتج',
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
            'lang_key' => 'Breakable',
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
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'est_order_pre_days',
            'lang_value' => 'الوحدات',
        ]);
    }
}
