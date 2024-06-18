<?php

namespace AryehRaber\Captcha\Listeners;

use Statamic\Events\FormSubmitted;
use Statamic\Forms\Submission;

class ValidateFormSubmission extends CaptchaListener
{
    /** @param FormSubmitted $event */
    protected function shouldVerify($event): bool
    {
        /** @var Submission */
        $submission = $event->submission;

        $shouldVerify = config('captcha.forms') === 'all'
            || in_array($submission->form()->handle(), config('captcha.forms', []));

        if ($shouldVerify && config('captcha.advanced_should_verify', null)) {
            $shouldVerify = app()->make(config('captcha.advanced_should_verify'))($submission);
        }

        return $shouldVerify;
    }
}
