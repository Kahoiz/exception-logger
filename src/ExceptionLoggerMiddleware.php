<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Illuminate\Support\Facades\Queue;
use kahoiz\ExceptionLogger\events\EventDispatcher;
use kahoiz\ExceptionLogger\jobs\LogException;


class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        if(!$response->exception) {

            return $response;
        }
        //JOB
        //LogException::dispatch($response->exception, $request->session()->getId())->onQueue('new-exception');

        //QUEUE
        //Queue::pushRaw(json_encode([
        //    'type' => get_class($response->exception),
        //    'message' => $response->exception->getMessage(),
        //    'file' => $response->exception->getFile(),
        //    'line' => $response->exception->getLine(),
        //    'trace' => $response->exception->getTraceAsString(),
        //    'sessionuid' => $request->session()->getId(),
        //    'environment' => env("APP_NAME"),
        //    'thrown_at' => now()
        //], JSON_THROW_ON_ERROR), 'new-exception');

        //EVENT
        $data = [
            'type' => get_class($response->exception),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'sessionuid' => $request->session()->getId(),
            'environment' => env("APP_NAME"),
            'thrown_at' => now()
        ];
        EventDispatcher::dispatch('exception.logged', $data);
        return $response;

    }

}
