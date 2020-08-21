<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Statamic\Forms\Submission;
use Statamic\Events\FormSubmitted;
use Illuminate\Validation\ValidationException;

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
        $submission = $event->form;

        if (! in_array($submission->form()->handle(), config('captcha.forms', []))) {
            return $submission;
        }

        if ($this->captcha->verify()->invalidResponse()) {
            throw ValidationException::withMessages(['captcha' => config('captcha.error_message')]);
        }

        return $submission;
    }
}
