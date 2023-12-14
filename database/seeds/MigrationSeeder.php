<?php

namespace Database\seeds;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('migrations')->insert([
            'id' => 1,
            'migration' => '2014_10_12_000000_create_users_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'id' => 2,
            'migration' => '2014_10_12_100000_create_password_resets_table',
            'batch' => 1
        ]);

        DB::table('migrations')->insert([
            'id' => 3,
            'migration' => '2019_10_13_000000_create_social_credentials_table',
            'batch' => 2
        ]);

        DB::table('migrations')->insert([
            'id' => 4,
            'migration' => '2021_06_07_000000_create_payku_transactions_',
            'batch' => 2
        ]);

        DB::table('migrations')->insert([
            'id' => 5,
            'migration' => '2021_06_07_000000_create_payku_transactions_table',
            'batch' => 2
        ]);

        DB::table('migrations')->insert([
            'id' => 6,
            'migration' => '2021_06_07_000001_create_payku_payments_table',
            'batch' => 0
        ]);

        DB::table('migrations')->insert([
            'id' => 7,
            'migration' => '2021_12_15_000000_add_new_columns_to_tables',
            'batch' => 2
        ]);

        DB::table('migrations')->insert([
            'id' => 8,
            'migration' => '2019_12_14_000001_create_personal_access_tokens_table',
            'batch' => 2
        ]);

    }
}
