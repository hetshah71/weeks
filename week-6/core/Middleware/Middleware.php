<?php

namespace Core\Middleware;

class Middleware
{
    public const MAP = [
        'guest' => Guest::class,
        'auth' => Authenticated::class
    ];

    public static function resolve($key)
    {
        if(!$key){
            return;
        }

        $middleware = static::MAP[$key] ?? false;
        // dd($middleware);

        if(!$middleware){
            throw new \Exception("Middleware {$key} not found");
        }

        (new $middleware)->handle();
    }
}