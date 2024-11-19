<?php

namespace kahoiz\ExceptionLogger\events;

class EventDispatcher
{
    protected static array $listeners = [];

    public static function listen(string $event, callable $listener): void
    {
        static::$listeners[$event][] = $listener;
    }

    public static function dispatch(string $event, $payload = null): void
    {
        if (!isset(static::$listeners[$event])) {
            return;
        }

        foreach (static::$listeners[$event] as $listener) {
            $listener($payload);
        }
    }
}
