<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Illuminate\Auth\Events\Login;

class ValidateUserLogin
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle(Login $event)
    {
        $user = $event->user;

        if (! $this->shouldVerify()) {
            return null;
        }

        $this->captcha->verify()->throwIfInvalid();

        return null;
    }

    protected function shouldVerify()
    {
        return config('captcha.user_login', false);
    }
}
