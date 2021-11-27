<?php

namespace AryehRaber\Captcha;

use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

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

    abstract public function renderIndexTag(Collection $params);

    abstract public function renderHeadTag();

    public function verify()
    {
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
     * @throws ValidationException if the validation failed.
     */
    public function throwIfInvalid()
    {
        if ($this->invalidResponse()) {
            $message = __('captcha::messages.validation_error');

            // Fallback for the old way of customizing the error message before github.com/aryehraber/statamic-captcha/pull/30
            $legacyMessage = config('captcha.error_message');
            if (! is_null($legacyMessage) && $legacyMessage !== 'Captcha failed.') {
                $message = $legacyMessage;
            }

            throw ValidationException::withMessages(['captcha' => $message]);
        }
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
