<?php

namespace AryehRaber\Captcha;

use Illuminate\Support\Collection;

class Recaptcha extends Captcha
{
    public function getResponseToken()
    {
        return request('g-recaptcha-response');
    }

    public function getVerificationUrl()
    {
        return 'https://www.google.com/recaptcha/api/siteverify';
    }

    public function getDefaultDisclaimer()
    {
        return 'This site is protected by reCAPTCHA and the Google [Privacy Policy](https://policies.google.com/privacy) and [Terms of Service](https://policies.google.com/terms) apply.';
    }

    public function renderIndexTag(Collection $params)
    {
        $attributes = $this->buildAttributes($params->merge([
            'data-sitekey' => $this->getSiteKey(),
            'data-size' => config('captcha.invisible') ? 'invisible' : '',
        ]));

        return "<div class=\"g-recaptcha\" {$attributes}></div>";
    }

    public function renderHeadTag()
    {
        return view('captcha::recaptcha.head', [
            'invisible' => config('captcha.invisible'),
            'hide_badge' => config('captcha.hide_badge'),
        ])->render();
    }
}
