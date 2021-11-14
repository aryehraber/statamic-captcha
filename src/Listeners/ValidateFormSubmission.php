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
        return in_array($submission->form()->handle(), config('captcha.forms', []));
    }
}
