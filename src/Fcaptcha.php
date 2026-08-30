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

    protected function getVerificationParams()
    {
        $params = parent::getVerificationParams();

        // FCaptcha binds a token to the IP address it saw when the widget was
        // solved, and rejects the token outright when 'remoteip' disagrees. The
        // two IPs are only guaranteed to match when the site and the FCaptcha
        // server sit behind the same proxy setup: request()->ip() is the
        // proxy's address until Laravel's trusted proxies are configured, and a
        // dual-stack visitor can reach one host over IPv6 and the other over
        // IPv4. Assert the IP only when the site opts in.
        if (! config('captcha.verify_ip')) {
            unset($params['remoteip']);
        }

        return $params;
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
