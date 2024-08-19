<?php

namespace AryehRaber\Captcha\Listeners;

use Statamic\Entries\Entry;
use Statamic\Events\EntrySaving;
use Statamic\Statamic;

class ValidateEntry extends CaptchaListener
{
    /** @param EntrySaving $event */
    protected function shouldVerify($event): bool
    {
        /** @var Entry */
        $entry = $event->entry;

        if (Statamic::isCpRoute()) {
            return false;
        }

        if (! $config = $this->getCollectionConfig($entry->collectionHandle())) {
            return false;
        }

        $skipFields = array_merge(['_token'], $config['skip_fields'] ?? []);

        return ! empty(request()->except($skipFields));
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
