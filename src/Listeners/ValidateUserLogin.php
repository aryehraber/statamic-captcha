<?php

namespace AryehRaber\Captcha\Listeners;

use Illuminate\Auth\Events\Login;

class ValidateUserLogin extends CaptchaListener
{
    /** @param Login $event */
    protected function shouldVerify($event): bool
    {
        return config('captcha.user_login', false);
    }
}
