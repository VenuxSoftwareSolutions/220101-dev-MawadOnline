<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountAuthenticator;

class AccountAuthenticatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $authenticators = [
            ['id' => 1, 'name' => 'MawadOnline'],
            ['id' => 2, 'name' => 'Google'],
            ['id' => 3, 'name' => 'Twitter'],
            ['id' => 4, 'name' => 'Facebook'],
            ['id' => 5, 'name' => 'Apple'],
        ];

        foreach ($authenticators as $authenticator) {
            AccountAuthenticator::updateOrCreate(['id' => $authenticator['id']], $authenticator);
        }
    }
}
