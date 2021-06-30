<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Illuminate\Validation\ValidationException;
use Statamic\Events\UserRegistering;

class ValidateUserRegistration
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle(UserRegistering $event)
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
        return config('captcha.user_registration', false);
    }
}
