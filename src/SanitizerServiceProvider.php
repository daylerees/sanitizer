<?php

namespace Rees\Sanitizer;

use Illuminate\Support\ServiceProvider;

class SanitizerServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('sanitizer', function ($app) {
            return new Sanitizer($app);
        });
    }
}
