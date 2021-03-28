<?php

namespace AryehRaber\Captcha;

use AryehRaber\Captcha\Listeners\ValidateEntry;
use AryehRaber\Captcha\Listeners\ValidateFormSubmission;
use AryehRaber\Captcha\Listeners\ValidateUserRegistration;
use Statamic\Events\EntrySaving;
use Statamic\Events\FormSubmitted;
use Statamic\Events\UserRegistering;
use Statamic\Providers\AddonServiceProvider;

class CaptchaServiceProvider extends AddonServiceProvider
{
    protected $viewNamespace = 'captcha';

    protected $tags = [
       CaptchaTags::class,
    ];

    protected $listen = [
        EntrySaving::class => [ValidateEntry::class],
        FormSubmitted::class => [ValidateFormSubmission::class],
        UserRegistering::class => [ValidateUserRegistration::class],
    ];

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function register()
    {
        $this->app->bind(Captcha::class, function () {
            $service = config('captcha.service');
            $class = "AryehRaber\\Captcha\\{$service}";

            throw_unless(class_exists($class), new \Exception('Invalid Captcha service.'));

            return new $class;
        });
    }
}
