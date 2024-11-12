<?php

namespace kahoiz\ExceptionLogger;

use Closure;



class ExceptionLoggerMiddleware
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);
        if(!$response->exception) {
            return $response;
        }
        $log = $this->createLog($request, $response);
        $log->save();



    }

    private function createLog($request, mixed $response)
    {
        $exception = $response->exception;
        $exceptionLog = new Exceptionlog();
        $exceptionLog->message = $exception->getMessage();
        $exceptionLog->file = $exception->getFile();
        $exceptionLog->line = $exception->getLine();
        $exceptionLog->trace = $exception->getTraceAsString();
        $exceptionLog->sessionuid = $request->session()->getId();
        return $exceptionLog;
    }


}
