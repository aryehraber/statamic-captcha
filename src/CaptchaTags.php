<?php

namespace AryehRaber\Captcha;

use Statamic\Support\Html;
use Statamic\Tags\Tags;

class CaptchaTags extends Tags
{
    protected static $handle = 'captcha';

    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    /**
     * The {{ captcha }} tag
     *
     * @return string
     */
    public function index()
    {
        return $this->captcha->renderIndexTag($this->params);
    }

    /**
     * The {{ captcha:head }} tag
     *
     * @return string
     */
    public function head()
    {
        return $this->captcha->renderHeadTag();
    }

    /**
     * The {{ captcha:disclaimer }} tag
     *
     * @return string
     */
    public function disclaimer()
    {
        if (! $disclaimer = config('captcha.disclaimer')) {
            $disclaimer = $this->captcha->getDefaultDisclaimer();
        }

        return Html::markdown($disclaimer);
    }

    /**
     * The {{ captcha:sitekey }} tag
     *
     * @return string
     */
    public function sitekey()
    {
        return $this->captcha->getSiteKey();
    }
}
