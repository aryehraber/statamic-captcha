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
     * The {{ captcha:selector }} tag
     *
     * @return string
     */
    public function selector()
    {
        return $this->captcha->getResponseSelector();
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

    /**
     * The {{ captcha:is_enabled form="insert_form_name_here" }} conditional tag.
     *
     * Use in an if statement to conditionally render based on whether captcha is enabled
     * for a form.
     */
    public function isEnabled(): bool
    {
        if ( ! $this->params->has('form')) {
            throw new \Exception('captcha:is_enabled requires the form parameter to be set');
        }

        $enabledForms = config('captcha.forms');

        return $enabledForms === 'all'
            || !in_array($this->params->get('form'), $enabledForms);
    }
}
