<?php

namespace kahoiz\ExceptionLogger\jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use kahoiz\ExceptionLogger\Exceptionlog;

class LogException implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected array $data;

    public function __construct(\Exception $exception, $sessionuid)
    {
        $this->data = [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'sessionuid' => $sessionuid,
            'environment' => env("APP_NAME")
        ];
    }


    public function handle(){
        $exceptionlog = new Exceptionlog($this->data);
        $exceptionlog->save();
    }


}
