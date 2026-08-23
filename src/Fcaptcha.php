<?php

namespace AryehRaber\Captcha;

use Illuminate\Support\Collection;

class Fcaptcha extends Captcha
{
    public function getResponseToken()
    {
        return request('fcaptcha_token');
    }

    public function getResponseSelector()
    {
        return 'input[name=fcaptcha_token]';
    }

    public function getVerificationUrl()
    {
        return $this->getServerUrl().'/turnstile/v0/siteverify';
    }

    public function getDefaultDisclaimer()
    {
        return '[Protected by FCaptcha](https://github.com/WebDecoy/FCaptcha).';
    }

    public function renderIndexTag(Collection $params)
    {
        $id = 'fcaptcha-'.bin2hex(random_bytes(6));
        $responseId = $id.'-response';
        $attributes = $this->buildAttributes($params->merge([
            'id' => $id,
            'class' => 'fcaptcha',
            'data-fcaptcha' => $this->getSiteKey(),
            'data-endpoint' => $this->getServerUrl(),
            'data-response-field' => $responseId,
        ]));

        return "<div {$attributes}></div><input type=\"hidden\" id=\"{$responseId}\" name=\"fcaptcha_token\">";
    }

    public function renderHeadTag()
    {
        return view('captcha::fcaptcha.head', [
            'serverUrl' => $this->getServerUrl(),
        ])->render();
    }

    protected function getServerUrl()
    {
        return rtrim(config('captcha.server_url'), '/');
    }
}
