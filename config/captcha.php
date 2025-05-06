<?php

return [
    'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha / Turnstile / Altcha
    'sitekey' => env('CAPTCHA_SITEKEY', ''),
    'secret' => env('CAPTCHA_SECRET', ''),
    'collections' => [],
    'forms' => [],
    'user_login' => false,
    'user_registration' => false,
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
    'custom_should_verify' => null,
    'script_url' => env('CAPTCHA_SCRIPT_URL'),
];
