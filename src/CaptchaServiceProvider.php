<?php

namespace AryehRaber\Captcha;

use AryehRaber\Captcha\Listeners;
use Statamic\Providers\AddonServiceProvider;

class CaptchaServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'captcha';

    protected $tags = [
       CaptchaTags::class,
    ];

    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [Listeners\ValidateUserLogin::class],
        \Statamic\Events\EntrySaving::class => [Listeners\ValidateEntry::class],
        \Statamic\Events\FormSubmitted::class => [Listeners\ValidateFormSubmission::class],
        \Statamic\Events\UserRegistering::class => [Listeners\ValidateUserRegistration::class],
    ];

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function boot()
    {
        parent::boot();

        $this->handleTranslations();
    }

    public function register()
    {
        $this->app->bind(Captcha::class, function () {
            $service = config('captcha.service');
            $class = "AryehRaber\\Captcha\\{$service}";

            throw_unless(class_exists($class), new \Exception('Invalid Captcha service.'));

            return new $class;
        });
    }

    protected function handleTranslations()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'captcha');
    }
}
