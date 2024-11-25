<?php

namespace App\Helpers;

use Firebase\JWT\JWT;

function generateAppleClientSecret()
{
    $key = env('APPLE_PRIVATE_KEY');
    $payload = [
        'iss' => env('APPLE_TEAM_ID'),
        'iat' => time(),
        'exp' => time() + (60 * 60 * 24), //here this generated apple secret key is valid for only one day 
        'aud' => 'https://appleid.apple.com',
        'sub' => env('APPLE_CLIENT_ID'),
    ];

    return JWT::encode($payload, $key, 'ES256', env('APPLE_KEY_ID'));
}
