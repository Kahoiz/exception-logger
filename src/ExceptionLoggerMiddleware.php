<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Dotenv\Dotenv;
use mysqli;


class ExceptionLoggerMiddleware
{
    private mysqli $mysqli;


    public function handle($request, Closure $next)
    {
        try {
            echo("ExceptionLogger start");
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
        // Load environment variables, base_path() is a laravel helper function, but this package is
        // suspected to only run in laravel applications, so it should be fine
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();


        $this->mysqli = new mysqli(
            $_ENV['DB_HOST'],
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            $_ENV['DB_DATABASE'],
            $_ENV['DB_PORT']
        );

        if ($this->mysqli->connect_error) {
            die('Connect Error (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error);
        }
    }

    private function logException(\Exception $e): void
    {
        try {
            echo("Logging exception: " . $e->getMessage() . "\n");
            $stmt = $this->mysqli->prepare('INSERT INTO exception_logs (message, file, line, trace) VALUES (?, ?, ?, ?)');
            $traceAsString = $e->getTraceAsString();
            $line = $e->getLine();
            $file = $e->getFile();
            $message = $e->getMessage();
            $stmt->bind_param(
                'ssis',
                $message,
                $file,
                $line,
                $traceAsString
            );
            $stmt->execute();
            echo("Exception logged successfully\n");
        } catch (\Exception $ex) {
            echo("Failed to log exception: " . $ex->getMessage() . "\n");
        }
    }

}
