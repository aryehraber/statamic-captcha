<?php

namespace AryehRaber\Captcha;

use Illuminate\Support\Collection;

class Altcha extends Captcha
{
    public function getResponseToken()
    {
        return request('altcha-payload');
    }

    public function getVerificationUrl()
    {
        return null;
    }

    public function getDefaultDisclaimer()
    {
        return '[Protected by ALTCHA](https://altcha.org).';
    }

    public function renderIndexTag(Collection $params)
    {
        $attributes = $this->buildAttributes($params->merge([
            'challengejson' => htmlspecialchars(json_encode($this->createChallenge())),
        ]));

        return "<div id=\"altcha-widget\" {$attributes}></div>";
    }

    public function renderHeadTag()
    {
        return view('captcha::altcha.head')->render();
    }

    public function verify()
    {
        $payload = json_decode(base64_decode($this->getResponseToken()), true);

        if ($payload) {
            $challenge = $this->createChallenge($payload['salt'], $payload['number']);

            $result = $payload['algorithm'] === $challenge['algorithm']
                    && $payload['challenge'] === $challenge['challenge']
                    && $payload['signature'] === $challenge['signature'];

            $this->data = collect(['success' => $result]);
        }

        return $this;
    }

    protected function createChallenge(string $salt = null, int $number = null): array
    {
        $salt = $salt ?? bin2hex(random_bytes(12));
        $number = $number ?? random_int(1e3, 1e5);

        $challenge = hash('sha256', $salt.$number);
        $signature = hash_hmac('sha256', $challenge, config('captcha.secret'));

        return [
            'algorithm' => 'SHA-256',
            'challenge' => $challenge,
            'salt' => $salt,
            'signature' => $signature,
        ];
    }
}
