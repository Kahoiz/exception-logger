<?php

use Illuminate\Database\QueryException;

/**
 * List of exceptions the middleware should ignore.
 */
return [
    'exceptions' => [
        QueryException::class,
    ],
    'application' => env('APP_NAME'),
    'environment' => env('APP_ENV'),
];
