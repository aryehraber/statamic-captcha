<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Illuminate\Validation\ValidationException;
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
            return $submission;
        }

        if ($this->captcha->verify()->invalidResponse()) {
            throw ValidationException::withMessages(['captcha' => config('captcha.error_message')]);
        }

        return $submission;
    }

    protected function shouldVerify(Submission $submission)
    {
        return in_array($submission->form()->handle(), config('captcha.forms', []));
    }
}
