<?php

namespace AryehRaber\Captcha\Listeners;

use AryehRaber\Captcha\Captcha;
use Illuminate\Validation\ValidationException;
use Statamic\Entries\Entry;
use Statamic\Events\EntrySaving;
use Statamic\Statamic;

class ValidateEntry
{
    protected $captcha;

    public function __construct(Captcha $captcha)
    {
        $this->captcha = $captcha;
    }

    public function handle(EntrySaving $event)
    {
        /** @var Entry */
        $entry = $event->entry;

        if (Statamic::isCpRoute()) {
            return $entry;
        }

        if (! in_array($entry->collectionHandle(), config('captcha.collections', []))) {
            return $entry;
        }

        if ($this->captcha->verify()->invalidResponse()) {
            throw ValidationException::withMessages(['captcha' => config('captcha.error_message')]);
        }

        return $entry;
    }
}
