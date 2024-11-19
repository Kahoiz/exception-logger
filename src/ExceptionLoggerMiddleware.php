<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Illuminate\Support\Facades\Queue;
use kahoiz\ExceptionLogger\jobs\LogException;


class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        if(!$response->exception) {

            return $response;
        }
//        LogException::dispatch($response->exception, $request->session()->getId())->onQueue('new-exception');
        Queue::pushRaw(json_encode([
            'type' => get_class($response->exception),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'sessionuid' => $request->session()->getId(),
            'environment' => env("APP_NAME"),
            'thrown_at' => now()
        ], JSON_THROW_ON_ERROR), 'new-exception');
        return $response;

    }

}
