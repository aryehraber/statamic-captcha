<?php

namespace AryehRaber\Captcha;

use Statamic\Http\Controllers\Controller;

class CaptchaController extends Controller
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * Get the captcha site key
     *
     * @return string
     */
    public function sitekey()
    {
        return response()->json(['sitekey' => $this->captcha->getSiteKey()]);
    }
}
