<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;

abstract class CaptchaListener
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle($event)
    {
        if ($this->shouldVerify($event)) {
            $this->captcha->verify()->throwIfInvalid();
        }

        return null;
    }

    abstract protected function shouldVerify($event): bool;
}
