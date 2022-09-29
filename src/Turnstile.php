<?php

namespace AryehRaber\Captcha;

use Illuminate\Support\Collection;

class Turnstile extends Captcha
{
    public function getResponseToken()
    {
        return request('cf-turnstile-response');
    }

    public function getVerificationUrl()
    {
        return 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    }

    public function getDefaultDisclaimer()
    {
        return 'This site is protected by Turnstile and the Cloudflare [Privacy Policy](https://www.cloudflare.com/privacypolicy) and [Terms of Service](https://www.cloudflare.com/website-terms) apply.';
    }

    public function renderIndexTag(Collection $params)
    {
        $attributes = $this->buildAttributes($params->merge([
            'data-sitekey' => $this->getSiteKey(),
        ]));

        return "<div class=\"cf-turnstile\" {$attributes}></div>";
    }

    public function renderHeadTag()
    {
        return view('captcha::turnstile.head')->render();
    }
}
