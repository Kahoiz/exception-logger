<?php
namespace kahoiz\ExceptionLogger;
use Closure;

class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {
        echo("ExceptionLogger start");
        $response = $next($request);
        echo("ExceptionLogger end");
        return $response;


    }
}
