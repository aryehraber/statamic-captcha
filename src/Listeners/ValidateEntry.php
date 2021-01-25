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

        if (! $this->shouldVerify($entry)) {
            return $entry;
        }

        if ($this->captcha->verify()->invalidResponse()) {
            throw ValidationException::withMessages(['captcha' => config('captcha.error_message')]);
        }

        return $entry;
    }

    protected function shouldVerify(Entry $entry)
    {
        if (Statamic::isCpRoute()) {
            return false;
        }

        if (! $config = $this->getCollectionConfig($entry->collectionHandle())) {
            return false;
        }

        return ! empty(request()->except($config['skip_fields'] ?? []));
    }

    protected function getCollectionConfig(string $handle)
    {
        $configs = collect(config('captcha.collections', []))->mapWithKeys(function ($val, $key) {
            return is_numeric($key) ? [$val => true] : [$key => $val];
        });

        if (! $config = $configs->get($handle)) {
            return null;
        }

        return is_array($config) ? $config : $handle;
    }
}
