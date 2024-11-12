<?php
namespace kahoiz\ExceptionLogger;
use Closure;


class ExceptionLoggerMiddleware
{
    private $pdo;
    public function __construct()
    {
        $config = require __DIR__ . '/../config.php';
        $dsn = "{$config['database']['driver']}:host={$config['database']['host']};port={$config['database']['port']};dbname={$config['database']['database']}";
        $username = $config['database']['username'];
        $password = $config['database']['password'];
        $this->pdo = new \PDO($dsn, $username, $password);

    }
    public function handle($request, Closure $next)
    {
        try {
            echo("ExceptionLogger start");
            $response = $next($request);

        } catch (\Exception $e) {
            echo("ExceptionLogger end with exception");
            $this->logException($e);
            //return the error message
            return $response;
        }
        echo("ExceptionLogger end");
        return $response;
    }
    private function logException(\Exception $e)
    {
        $query = $this->pdo->prepare('INSERT INTO exception_logs (message, file, line, trace) VALUES (:message, :file, :line, :trace)');
        $query->execute([
            ':message' => $e->getMessage(),
            ':file' => $e->getFile(),
            ':line' => $e->getLine(),
            ':trace' => $e->getTraceAsString(),
        ]);
    }
}
