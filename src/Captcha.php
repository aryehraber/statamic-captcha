<?php

namespace AryehRaber\Captcha;

use GuzzleHttp\Client;

abstract class Captcha
{
    protected $client;

    protected $data;

    public function __construct()
    {
        $this->client = new Client(['http_errors' => false]);
    }

    abstract public function getResponseToken();

    abstract public function getVerificationUrl();

    abstract public function getDefaultDisclaimer();

    abstract public function renderIndexTag();

    abstract public function renderHeadTag();

    public function verify()
    {
        if (request()->boolean('disable_captcha', false)) {
            $this->data = collect(['success' => true]);

            return $this;
        }

        $query = [
            'secret' => $this->getSecret(),
            'response' => $this->getResponseToken(),
            'remoteip' => request()->ip(),
        ];

        $response = $this->client->post($this->getVerificationUrl(), compact('query'));

        if ($response->getStatusCode() == 200) {
            $this->data = collect(json_decode($response->getBody(), true));
        }

        return $this;
    }

    /**
     * Check whether the response was valid
     *
     * @return bool
     */
    public function validResponse()
    {
        if (is_null($this->data)) {
            return false;
        }

        if (! $this->data->get('success')) {
            return false;
        }

        return true;
    }

    /**
     * Check whether the response was invalid
     *
     * @return bool
     */
    public function invalidResponse()
    {
        return ! $this->validResponse();
    }

    /**
     * Get the configured Captcha Site Key
     *
     * @return string
     */
    public function getSiteKey()
    {
        return config('captcha.sitekey');
    }

    /**
     * Get the configured Captcha Secret
     *
     * @return string
     */
    public function getSecret()
    {
        return config('captcha.secret');
    }

    /**
     * Get the current domain, excluding 'http(s)://'
     *
     * @return string
     */
    protected function currentDomain()
    {
        return preg_split('/http(s)?:\/\//', url())[1];
    }

    /**
     * Helper to build HTML element attributes string
     *
     * @return string
     */
    protected function buildAttributes($attributes)
    {
        return collect($attributes)->filter()->map(function ($value, $key) {
            return sprintf('%s="%s"', $key, $value);
        })->implode(' ');
    }
}
