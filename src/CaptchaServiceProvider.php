<?php

namespace AryehRaber\Captcha;

use Statamic\Events\FormSubmitted;
use Statamic\Providers\AddonServiceProvider;
use AryehRaber\Captcha\Listeners\ValidateFormSubmission;

class CaptchaServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'captcha';

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
