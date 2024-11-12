<?php
namespace kahoiz\ExceptionLogger;
use Closure;

class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {
        echo("ExceptionLogger start");
        $response = $next($request);
        if (!$response->exception) {
            echo("ExceptionLogger end");
            return $response;
        }
        echo($response->exception);
        return "ExceptionLogger end with exception";


    }
}
