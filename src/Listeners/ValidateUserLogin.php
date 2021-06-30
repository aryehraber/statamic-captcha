<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Illuminate\Auth\Events\Login;
use Illuminate\Validation\ValidationException;

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

        if ($this->captcha->verify()->invalidResponse()) {
            throw ValidationException::withMessages(['captcha' => config('captcha.error_message')]);
        }

        return null;
    }

    protected function shouldVerify()
    {
        return config('captcha.user_login', false);
    }
}
