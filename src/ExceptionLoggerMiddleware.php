<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use kahoiz\ExceptionLogger\jobs\LogException;


class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        if(!$response->exception) {

            return $response;
        }
        LogException::dispatch($response->exception, $request->session()->getId())->onQueue('new-exception');

        return $response;

    }

}
