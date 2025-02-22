<?php

namespace AryehRaber\Captcha;

use Illuminate\Support\Collection;

class Hcaptcha extends Captcha
{
    public function getResponseToken()
    {
        return request('h-captcha-response');
    }

    public function getResponseSelector()
    {
        return '.h-captcha textarea[name=h-captcha-response]';
    }

    public function getVerificationUrl()
    {
        return 'https://hcaptcha.com/siteverify';
    }

    public function getDefaultDisclaimer()
    {
        return 'This site is protected by hCaptcha and its <a href="https://hcaptcha.com/privacy">Privacy Policy</a> and <a href="https://hcaptcha.com/terms">Terms of Service</a> apply.';
    }

    public function renderIndexTag(Collection $params)
    {
        $attributes = $this->buildAttributes($params->merge([
            'data-sitekey' => $this->getSiteKey(),
            'data-size' => config('captcha.invisible') ? 'invisible' : $params->get('data-size'),
        ]));

        return "<div class=\"h-captcha\" {$attributes}></div>";
    }

    public function renderHeadTag()
    {
        return view('captcha::hcaptcha.head', [
            'invisible' => config('captcha.invisible'),
        ])->render();
    }
}
