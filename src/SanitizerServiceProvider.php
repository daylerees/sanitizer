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
        $this->app['sanitizer'] = $this->app->share(function ($app) {
            return new Sanitizer($this->app);
        });
    }
}
