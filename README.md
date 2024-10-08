# Captcha

**Protect your Statamic forms using a Captcha service.**

This addon allows you to protect your Statamic forms using any of the following services:
- [Google reCAPTCHA v2](https://developers.google.com/recaptcha/intro) (v3 not supported)
- [hCaptcha](https://hcaptcha.com/?r=eaeeea7cd23c)
- [Cloudflare Turnstile](https://developers.cloudflare.com/turnstile)
- [ALTCHA](https://altcha.org)

After the initial setup, all you need to do is add the `{{ captcha }}` tag inside your forms, easy peasy!

## Installation

Install the addon via composer:

```
composer require aryehraber/statamic-captcha
```

Publish the config file:

```
 php please vendor:publish --tag=captcha-config
```

Alternately, you can manually setup the config file by creating `captcha.php` inside your project's `config` directory:

```php
<?php

return [
    'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha / Turnstile / Altcha
    'sitekey' => env('CAPTCHA_SITEKEY', ''),
    'secret' => env('CAPTCHA_SECRET', ''),
    'forms' => [],
    'user_login' => false,
    'user_registration' => false,
    'disclaimer' => '',
    'invisible' => false,
    'hide_badge' => false,
    'enable_api_routes' => false,
    'custom_should_verify' => null,
];
```

Once the config file is in place, make sure to add your `sitekey` & `secret` from [Recaptcha's Console](https://www.google.com/recaptcha/admin), [hCaptcha's Console](https://dashboard.hcaptcha.com/sites), [Cloudflare's Dashboard](https://dash.cloudflare.com) or [Altcha's Docs](https://altcha.org/docs/api/api_keys/) and add the handles of the Statamic Forms you'd like to protect:

```php
<?php

return [
    'service' => 'Recaptcha', // options: Recaptcha / Hcaptcha / Turnstile / Altcha
    'sitekey' => 'YOUR_SITEKEY_HERE', // Or add to .env
    'secret' => 'YOUR_SECRET_HERE', // Or add to .env
    'forms' => ['contact', 'newsletter'],
    // ...
];
```

If you would like Captcha to verify ALL forms without having to specify each one in the `forms` config array, you may use the `all` option instead.

_Note: this should replace the array and be set as a string._

```php
<?php

return [
    'forms' => 'all',
    // ...
];
```

## Usage

```html
<head>
    <title>My Awesome Site</title>

    {{ captcha:head }}
</head>
<body>
    {{ form:contact }}

        <!-- Add your fields like normal -->

        {{ captcha }}

        {{ if error:captcha }}
          <p>{{ error:captcha }}</p>
        {{ /if }}

    {{ /form:contact }}
</body>
```

This will automatically render the Captcha element on the page. After the form is submitted, the addon will temporarily halt the form from saving while the Captcha service verifies that the request checks out. If all is good, the form will save as normal, otherwise an error will be added to the `{{ errors }}` object.

## Invisible Captcha

Simply set `invisible` to `true` inside Captcha's config (Turnstile handles invisibility from Cloudflares's Dashboard, so no Captcha config changes are needed). To hide the sticky Recaptcha badge, make sure to also set `hide_badge` to `true`.

Note: using Invisible Captcha will require you to display links to the Captcha service's Terms underneath the form, to make this easier use `{{ captcha:disclaimer }}`. This message can be customised using the `disclaimer` option inside Captcha's config, however sensible defaults have been added that will automatically switch depending on the Captcha service you're using.

## User Registration & Login

Captcha can also verify [User Registration](https://statamic.dev/tags/user-register_form) & [User Login](https://statamic.dev/tags/user-login_form) form requests, simply set `user_registration` / `user_login` to `true` inside Captcha's config and use the `{{ captcha }}` tag as normal inside Statamic's `{{ user:register_form }}` / `{{ user:login_form }}` tags.

## Data Attributes

Some of the Captcha services offer additional features, such as light/dark mode and sizing options, via data attributes. These can simply be added to the Captcha tag and will be passed through to the client-side widget.

```
{{ captcha data-theme="dark" data-size="compact" }}
```

## Translations

This package is localized to English and German.
If you need translations in another language, you can create them yourself:

* Create the translations file in `resources/lang/vendor/statamic-captcha/{language}/messages.php`.
* You can use the [English translation file](https://github.com/aryehraber/statamic-captcha/blob/master/resources/lang/en/messages.php) as a blueprint.
* You are welcome to share your translations here by [submitting a PR](https://github.com/aryehraber/statamic-captcha/pulls).

If you want to change existing messages, you can publish and override them:

```
php please vendor:publish --tag="captcha-translations"
```

## Advanced

### Custom "Should Verify" Logic

You can provide additional custom logic to determine if verification should be attempted using a custom invokable class. This is useful to adjust Captcha's `shouldVerify` check to include app-specific behaviour, for example: only enforcing Captcha for guest users and bypassing it for logged-in users.

To get started, create an invokable class within your app, and add it as the `custom_should_verify` property in your config:

```php
<?php

return [
    // ...
    'custom_should_verify' => \App\Support\MyCustomShouldVerify::class,
];
```

This invokable class must implement the `\AryehRaber\Captcha\Contracts\CustomShouldVerify` interface, which enforces that an event param gets passed into the invoke method and subsequently returns an optional boolean. To stop Captcha's `shouldVerify` method from getting called, the invoke method must return `false`, otherwise returning `true` (or `null`) will continue Captcha's verification logic.

Note: this custom class is resolved via Laravel's container, meaning dependency injection is available via the constructor.

```php
<?php

namespace App\Support;

use AryehRaber\Captcha\Contracts\CustomShouldVerify;

class MyCustomShouldVerify implements CustomShouldVerify
{
    public function __invoke($event): ?bool
    {
        // bypass verification for authenticated users
        if (auth()->check()) {
            return false;
        }

        // bypass verification on dev environment
        if (app()->environment('dev')) {
            return false;
        }

        // bypass verification based on event form submission
        if ($event instanceof \Statamic\Events\FormSubmitted) {
            // return $event->submission;
        }

        // bypass verification based on login event
        if ($event instanceof \Illuminate\Auth\Events\Login) {
            // return $event->user;
        }

        // bypass verification based on user registration event
        if ($event instanceof \Statamic\Events\UserRegistering) {
            // return $event->user;
        }

        // bypass verification based on entry saving event
        if ($event instanceof \Statamic\Events\EntrySaving) {
            // return $event->entry;
        }
    }
}
```
