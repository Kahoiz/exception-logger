<?php
namespace kahoiz\ExceptionLogger;
use Closure;

class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {
        try {
            echo("ExceptionLogger start");
            $response = $next($request);

        } catch (\Exception $e) {
            echo("ExceptionLogger end with exception");
            //return the error message
            return $e->getMessage();
        }
        echo("ExceptionLogger end");
        return $response;
    }
}
