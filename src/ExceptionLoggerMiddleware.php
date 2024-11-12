<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Dotenv\Dotenv;
use PDO;


class ExceptionLoggerMiddleware
{
    private PDO $pdo;

    public function init()
    {
        $dotenv = Dotenv::createImmutable(base_path());
        $dotenv->load();

        $dsn = "{$_ENV['DB_DRIVER']}:host={$_ENV['DB_HOST']};port={$_ENV['DB_PORT']};dbname={$_ENV['DB_DATABASE']}";
        $username = $_ENV['DB_USERNAME'];
        $password = $_ENV['DB_PASSWORD'];
        $this->pdo = new \PDO($dsn, $username, $password);
    }

    public function handle($request, Closure $next)
    {
        try {
            echo("ExceptionLogger start");
            $response = $next($request);


        } catch (\Exception $e) {
            echo("ExceptionLogger end with exception");
            $this->init();
            $this->logException($e);
            //return the error message
            return $response;
        }
        echo("ExceptionLogger end");
        return $response;
    }

    private function logException(\Exception $e): void
    {
        try {
            echo("Logging exception: " . $e->getMessage() . "\n");
            $query = $this->pdo->prepare('INSERT INTO exception_logs (message, file, line, trace) VALUES (:message, :file, :line, :trace)');
            $query->execute([
                ':message' => $e->getMessage(),
                ':file' => $e->getFile(),
                ':line' => $e->getLine(),
                ':trace' => $e->getTraceAsString(),
            ]);
            echo("Exception logged successfully\n");
        } catch (\Exception $ex) {
            echo("Failed to log exception: " . $ex->getMessage() . "\n");
        }
    }

}
