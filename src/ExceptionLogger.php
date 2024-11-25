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

        if(!$response->exception) {

            return $response;
        }
        //No desire to log exceptions in local environment
        if (env('APP_ENV') !== 'production') {
            return $response;
        }

        $data = [
            'type' => get_class($response->exception),
            'code' => $response->exception->getCode(),
            'message' => $response->exception->getMessage(),
            'file' => $response->exception->getFile(),
            'line' => $response->exception->getLine(),
            'trace' => $response->exception->getTraceAsString(),
            'uuid' => (string) Str::uuid(),
            'application' => env("APP_NAME"),
            'user_id' => $request->user()->id ?? null,
            'thrown_at' => now()->format('Y-m-d H:i:s')
        ];
        //In laravel 8, pushRaw doesn't automatically encode the array to a json string, so we'll have to do it manually

        if ($this->validate($data)) {
            Queue::pushRaw(json_encode($data), 'new-exception');
        }
        else {
            Queue::pushRaw(json_encode($data), 'invalid-exception');
        }
        return $response;

    }

    private function validate(array $data) : bool
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
            'thrown_at' => 'required|date'
        ]);
        return $validator->passes();
    }

}
