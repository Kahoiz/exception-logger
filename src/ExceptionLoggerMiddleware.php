<?php

namespace kahoiz\ExceptionLogger;

use Closure;



class ExceptionLoggerMiddleware
{
    private mysqli $mysqli;


    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);


        } catch (\Exception $e) {
            echo("Yes exception");
            $this->init();
            $this->logException($e);
            return "An error occurred";
        }
        echo("No exception end");
        return $response;
    }

    public function init()
    {

    }

    private function logException(\Exception $e): void
    {

    }

}
