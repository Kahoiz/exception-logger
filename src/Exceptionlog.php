<?php

namespace kahoiz\ExceptionLogger;

use Illuminate\Database\Eloquent\Model;

class Exceptionlog extends Model
{
    public $timestamps = false;

    protected $table = 'exception_logs';

    protected $fillable = [
        'type',
        'message',
        'file',
        'line',
        'trace',
        'sessionuid',
        'environment',
        'thrown_at'
    ];
}
