<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;


class ExceptionLogger
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        if(!$response->exception) {

            return $response;
        }
        //In laravel 8, pushRaw expects a string as the first argument, so we'll have to encode the array to a json string
        Queue::pushRaw(json_encode([
            'type' => get_class($response->exception),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'uuid' => (string) Str::uuid(),
            'environment' => env("APP_NAME"),
            'thrown_at' => now()->format('Y-m-d H:i:s')
        ], JSON_THROW_ON_ERROR), 'new-exception');

        return $response;

    }

}
