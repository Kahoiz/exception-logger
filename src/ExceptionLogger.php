<?php

namespace kahoiz\ExceptionLogger;

use Closure;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class ExceptionLogger
{


    public function handle($request, Closure $next)
    {

        $response = $next($request);

        //No desire to log exceptions in local environment
        if (config('exceptions.environment') !== 'production') {
            return $response;
        }
        //No exception, no need to log
        if (!$response->exception) {

            return $response;
        }
        //Check if the exception is configured in the config file
        $exceptionsToIgnore = config('exceptions.exceptions');
        if (in_array(get_class($response->exception), $exceptionsToIgnore, true)) {
            return $response;
        }

        $uuid = (string)Str::uuid();

        $data = [
            'type' => get_class($response->exception),
            'code' => $response->exception->getCode(),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'uuid' => $uuid,
            'application' => config('exceptions.application'),
            'user_id' => $request->user()->id ?? null,
            'thrown_at' => now()->format('Y-m-d H:i:s'),
            'previous' => $this->getPreviousExceptionData($response->exception, $uuid)
        ];

        if ($this->validate($data)) {
            Queue::pushRaw(json_encode($data), 'new-exception');
        } else {
            Queue::pushRaw(json_encode($data), 'invalid-exception');
        }
        return $response;

    }

    private function getPreviousExceptionData($exception, $uuid): ?array
    {
        if (!$exception->getPrevious()) {
            return null;
        }

        $previous = $exception->getPrevious();

        return [
            'type' => get_class($previous),
            'code' => $previous->getCode(),
            'message' => $previous->getMessage(),
            'file' => $previous->getFile(),
            'line' => $previous->getLine(),
            'trace' => $previous->getTraceAsString(),
            'uuid' => $uuid,
            'application' => config('exceptions.application'),
            'thrown_at' => now()->format('Y-m-d H:i:s'),
            'previous' => $this->getPreviousExceptionData($previous, $uuid),

        ];
    }

    private function validate(array $data): bool
    {
        $validator = Validator::make($data, [
            'type' => 'required|string',
            'code' => 'required|integer',
            'message' => 'required|string',
            'file' => 'required|string',
            'line' => 'required|integer',
            'trace' => 'required|string',
            'uuid' => 'required|string',
            'application' => 'required|string',
            'user_id' => 'nullable|integer',
            'thrown_at' => 'required|date',
            'previous' => 'nullable|array',
        ]);
        return $validator->passes();
    }

}
