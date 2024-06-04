<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationCatalogueSeeder extends Seeder
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
            'lang_key' => 'mawad_catalog_search_page',
            'lang_value' => 'صفحة بحث كتالوج مواد',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'easily_find_products_from_other_vendors_here_if_you_cant_find_yours_no_worries_you_can_always_add_them_manually_save_time_and_keep_your_listings_consistent',
            'lang_value' => 'ابحث بسهولة عن المنتجات من الموردين الآخرين هنا! إذا لم تتمكن من العثور على منتجاتك، لا تقلق، يمكنك دائمًا إضافتها يدويًا. وفر الوقت وحافظ على اتساق قوائمك!',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'create_product_manually',
            'lang_value' => 'إنشاء منتج يدويًا',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'search_by_product_name_model_brand_',
            'lang_value' => 'البحث حسب اسم المنتج، الموديل، العلامة التجارية …',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_search_to_get_the_list_of_product_catalogue_',
            'lang_value' => 'يرجى البحث للحصول على قائمة كتالوج المنتجات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'please_fill_in_the_search_input_before_browsing_the_catalog',
            'lang_value' => 'يرجى ملء حقل البحث قبل تصفح الكتالوج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'cancel',
            'lang_value' => 'إلغاء',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'number_of_variants_',
            'lang_value' => 'عدد المتغيرات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'showing',
            'lang_value' => 'عرض',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'of',
            'lang_value' => 'من',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'view_product',
            'lang_value' => 'عرض المنتج',
        ]);
        DB::table('translations')->insert([
            'lang' => 'ae',
            'lang_key' => 'no_catalog_found',
            'lang_value' => 'لم يتم العثور على كتالوج',
        ]);
    }
}
