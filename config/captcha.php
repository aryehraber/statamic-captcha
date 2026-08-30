<?php

return [
    'service' => env('CAPTCHA_SERVICE', 'Recaptcha'), // options: Recaptcha / Hcaptcha / Turnstile / Altcha / Fcaptcha
    'sitekey' => env('CAPTCHA_SITEKEY', ''),
    'secret' => env('CAPTCHA_SECRET', ''),
    'server_url' => env('CAPTCHA_SERVER_URL', ''), // required for Fcaptcha
    'verify_ip' => env('CAPTCHA_VERIFY_IP', false), // Fcaptcha only, see README
    'collections' => [],
    'forms' => [],
    'user_login' => false,
    'user_registration' => false,
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
    'custom_should_verify' => null,
];
