<?php

namespace kahoiz\ExceptionLogger;

use Illuminate\Support\ServiceProvider;

class ExceptionLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/exceptions.php' => config_path('exceptions.php'),
        ], 'config');
    }
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/exceptions.php', 'exceptions');
    }
}

