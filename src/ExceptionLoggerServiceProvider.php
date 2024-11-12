<?php

namespace kahoiz\ExceptionLogger;

use Illuminate\Support\ServiceProvider;

class ExceptionLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        //
    }
}
