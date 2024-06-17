<?php

namespace AryehRaber\Captcha\Listeners;

use Statamic\Events\UserRegistering;

class ValidateUserRegistration extends CaptchaListener
{
    /** @param UserRegistering $event */
    protected function shouldVerify($event): bool
    {
        return config('captcha.user_registration', false);
    }
}
