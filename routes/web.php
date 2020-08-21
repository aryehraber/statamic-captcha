<?php

use Illuminate\Support\Facades\Route;
use AryehRaber\Captcha\CaptchaController;

if (config('captcha.enable_api_routes')) {
    Route::get(config('statamic.routes.action').'/captcha/sitekey', [CaptchaController::class, 'sitekey']);
}
