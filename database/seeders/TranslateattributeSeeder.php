<?php

namespace Database\seeds;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslateattributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'display_name_in_english_version',
            'lang_value' => 'عرض الاسم في النسخة الإنجليزية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'display_name_in_arabic_version',
            'lang_value' => 'عرض الاسم في النسخة العربية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'value_type',
            'lang_value' => 'نوع القيمة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'value_type_is_required',
            'lang_value' => 'نوع القيمة مطلوب',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'please_choose_type',
            'lang_value' => 'الرجاء اختيار النوع',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'short_description',
            'lang_value' => 'وصف قصير',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'english_description',
            'lang_value' => 'الوصف الانكليزي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'arabic_description',
            'lang_value' => 'الوصف العربي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'value_in_english_version',
            'lang_value' => 'القيمة في النسخة الإنكليزية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'value_in_english',
            'lang_value' => 'القيمة بالإنكليزية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'value_in_arabic',
            'lang_value' => 'القيمة باللغة العربية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'units',
            'lang_value' => 'وحدات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'click_to_select_a_unit',
            'lang_value' => 'انقر لتحديد الوحدة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'add_another_values',
            'lang_value' => 'أضف قيم أخرى',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'delete_this_values',
            'lang_value' => 'حذف هذه القيمة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'delete_this_value',
            'lang_value' => 'حذف هذه القيمة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'this_value_will_be_deleted_in_both_english_and_arabic_sections',
            'lang_value' => 'ستحذف هذه القيمة في القسمين الانكليزي والعربي!',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'cancelled',
            'lang_value' => 'ألغيت',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'your_deletion_is_undone',
            'lang_value' => 'تم التراجع عن حذفك',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'error',
            'lang_value' => 'خطأ',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'cannot_delete_this_value_because_is_used_in_product',
            'lang_value' => 'لا يمكن حذف هذه القيمة لأنه يستخدم في المنتج!',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'are_you_sure_you_want_to_delete',
            'lang_value' => 'هل أنت متأكد من أنك تريد حذف ؟',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'attribute_name_already_existe',
            'lang_value' => 'اسم الصفة موجود بالفعل',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'attribute_has_been_updated_successfully',
            'lang_value' => 'تم تحديث السمة بنجاح',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'attribute_has_been_inserted_successfully',
            'lang_value' => 'تم إدراج الصفة بنجاح',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'attribute_informations',
            'lang_value' => 'معلومات السمات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'the_name_should_be_unique',
            'lang_value' => 'يجب أن يكون الاسم فريدًا.',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'add_new_attribute',
            'lang_value' => 'أضف سمة جديدة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'add_new_attribute_value',
            'lang_value' => 'اضف قيمة جديدة للسمة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'all_attributes',
            'lang_value' => 'جميع سمات',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'values',
            'lang_value' => 'قيم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'list_of_values',
            'lang_value' => 'قائمة القيم',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'text',
            'lang_value' => 'نص',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'numeric',
            'lang_value' => 'رقمي',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'boolean',
            'lang_value' => 'منطقية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'name_in_english',
            'lang_value' => 'الاسم بالإنكليزية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'name_in_arabic',
            'lang_value' => 'الاسم بالعربية',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'add_new_unit',
            'lang_value' => 'إضافة وحدة جديدة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'add_unit',
            'lang_value' => 'أضف وحدة',
        ]);
        DB::table('translations')->insert([
            'lang' => 'sa',
            'lang_key' => 'all_units',
            'lang_value' => 'جميع الوحدات',
        ]);
    }
}
