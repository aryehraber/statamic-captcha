<?php

namespace AryehRaber\Captcha;

use Illuminate\Http\Response;
use Statamic\Facades\StaticCache;
use Statamic\StaticCaching\Replacer;

class CaptchaReplacer implements Replacer
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function prepareResponseToCache(Response $response, Response $initial)
    {
        if (! $content = $response->getContent()) {
            return;
        }

        StaticCache::includeJs();

        $content = $this->replaceInitCall($response, $content);
        $content = $this->replaceScript($response, $content);

        $response->setContent($content);
    }

    public function replaceInCachedResponse(Response $response)
    {
        //
    }

    protected function replaceInitCall(Response $response, string $content)
    {
        $search = "document.addEventListener('DOMContentLoaded', initCaptcha);";
        $replace = "document.addEventListener('statamic:nocache.replaced', () => setTimeout(initCaptcha, 100));";

        return str_replace($search, $replace, $content);
    }

    protected function replaceScript(Response $response, string $content)
    {
        $url = $this->captcha->getScriptUrl();

        $search = view('captcha::base-script', ['url' => $url])->render();
        $replace = view('captcha::nocache-script', ['url' => $url])->render();

        return str_replace($search, $replace, $content);
    }
}
