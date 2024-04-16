<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranslationsRegisterVendorPart2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $translations = [
            // ['ae', 'please_review_the_form_for_errors', 'يرجى مراجعة النموذج بحثًا عن الأخطاء'],
            // ['ae', 'previous', 'السابق'],
            // ['ae', 'login_to_seller', 'تسجيل الدخول إلى البائع'],
            // ['ae', 'become_a_seller_', 'كن بائعًا'],
            // ['ae', 'a_6digit_code', 'تم إرسال رمز مكون من 6 أرقام إلى بريدك الإلكتروني.'],
            // ['en', 'a_6digit_code', 'A 6-digit code has been sent to your email.'],
            ['ae', 'the_mobile_phone_number_must_be_a_valid_uae_number_including_the_country_code_971', 'يجب أن يكون رقم الهاتف المحمول صحيحًا ويحتوي على رمز البلد +971 للإمارات.'],


        ] ;
        foreach ($translations as $translation) {
            DB::table('translations')->insert([

                'lang' => $translation[0],
                'lang_key' => $translation[1],
                'lang_value' => $translation[2],

            ]);
        }

    }
}
