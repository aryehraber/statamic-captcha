<?php

return [
    'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha
    'sitekey' => env('CAPTCHA_SITEKEY', ''),
    'secret' => env('CAPTCHA_SECRET', ''),
    'forms' => [],
    'error_message' => 'Captcha failed.',
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
];
