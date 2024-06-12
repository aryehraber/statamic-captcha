<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Statamic\Events\FormSubmitted;
use Statamic\Forms\Submission;

class ValidateFormSubmission
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle(FormSubmitted $event)
    {
        /** @var Submission */
        $submission = $event->submission;

        if (! $this->shouldVerify($submission)) {
            return null;
        }

        $this->captcha->verify()->throwIfInvalid();

        return null;
    }

    protected function shouldVerify(Submission $submission)
    {
        $shouldVerify = config('captcha.forms') === 'all'
            || in_array($submission->form()->handle(), config('captcha.forms', []));

        if ($shouldVerify && config('captcha.advanced_should_verify', null)) {
            $shouldVerify = app()->make(config('captcha.advanced_should_verify'))($submission);
        }

        return $shouldVerify;
    }
}
