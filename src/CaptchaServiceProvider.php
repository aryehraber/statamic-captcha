<?php

namespace AryehRaber\Captcha;

use Statamic\Events\FormSubmitted;
use Statamic\Providers\AddonServiceProvider;
use AryehRaber\Captcha\Listeners\ValidateFormSubmission;

class CaptchaServiceProvider extends AddonServiceProvider
{
    protected $tags = [
       CaptchaTags::class,
    ];

    protected $listen = [
        FormSubmitted::class => [
            ValidateFormSubmission::class,
        ],
    ];

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'captcha');

        $this->mergeConfigFrom(__DIR__.'/../config/captcha.php', 'captcha');

        $this->publishes([
            __DIR__.'/../config/captcha.php' => config_path('captcha.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/captcha'),
        ], 'views');
    }

    public function register()
    {
        $this->app->bind(Captcha::class, function() {
            $service = config('captcha.service');
            $class = "AryehRaber\\Captcha\\{$service}";

            throw_unless(class_exists($class), new \Exception('Invalid Captcha service.'));

            return new $class;
        });
    }
}
