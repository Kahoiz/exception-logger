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
        //dispatch a job to log the exception
        LogException::dispatch($response->exception, $request->session()->getId);


    }

}
