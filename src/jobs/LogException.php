<?php

namespace kahoiz\ExceptionLogger\jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class LogException implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $exception;
    protected $sessionuid;

    /**
     * Create a new job instance.
     *
     * @param \Exception $exception
     * @return void
     */
    public function __construct($exception, $sessionuid)
    {
        $data = [
            'message' => $this->exception->getMessage(),
            'file' => $this->exception->getFile(),
            'line' => $this->exception->getLine(),
            'trace' => $this->exception->getTraceAsString(),
            'sessionuid' => $sessionuid,
            'environment' => env("APP_NAME")
        ];
    }


}
