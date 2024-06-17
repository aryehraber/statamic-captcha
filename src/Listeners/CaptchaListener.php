<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use AryehRaber\Captcha\Contracts\CustomShouldVerify;
use Illuminate\Support\Facades\App;

abstract class CaptchaListener
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle($event)
    {
        $customShouldVerify = $this->getCustomShouldVerifyClass();

        if ($customShouldVerify && $customShouldVerify($event) === false) {
            return null;
        }

        if ($this->shouldVerify($event)) {
            $this->captcha->verify()->throwIfInvalid();
        }

        return null;
    }

    abstract protected function shouldVerify($event): bool;

    protected function getCustomShouldVerifyClass(): ?CustomShouldVerify
    {
        $customClass = config('captcha.custom_should_verify');

        if (! class_exists($customClass)) {
            return null;
        }

        return App::make($customClass);
    }
}
