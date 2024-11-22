<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Illuminate\Support\Facades\Queue;


class ExceptionLogger
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        if(!$response->exception) {

            return $response;
        }

        //QUEUE
        Queue::pushRaw(json_encode([
            'type' => get_class($response->exception),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'sessionuid' => $request->session()->getId(),
            'environment' => env("APP_NAME"),
            'thrown_at' => now()->format('Y-m-d H:i:s')
        ], JSON_THROW_ON_ERROR), 'new-exception');

        //EVENT
//        $data = [
//            'type' => get_class($response->exception),
//            'message' => $response->exception->getMessage(),
//            'file' => $response->exception->getFile(),
//            'line' => $response->exception->getLine(),
//            'trace' => $response->exception->getTraceAsString(),
//            'sessionuid' => $request->session()->getId(),
//            'environment' => env("APP_NAME"),
//            'thrown_at' => now()
//        ];
//        NewException::dispatch($data);
        return $response;

    }

}
