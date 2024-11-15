<?php

namespace kahoiz\ExceptionLogger;

use Illuminate\Database\Eloquent\Model;

class Exceptionlog extends Model
{

    protected $table = 'exception-logs';

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
