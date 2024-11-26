<?php
use Illuminate\Database\QueryException;

/**
 * List of exceptions the middleware should ignore.
 */
return [
    'exceptions' => [
        QueryException::class,
    ]
];
