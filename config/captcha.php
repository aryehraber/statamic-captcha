<?php

return [
    'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha
    'sitekey' => env('CAPTCHA_SITEKEY', ''),
    'secret' => env('CAPTCHA_SECRET', ''),
    'collections' => [],
    'forms' => [],
    'user_login' => false,
    'user_registration' => false,
    'error_message' => 'Captcha failed.',
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
];
