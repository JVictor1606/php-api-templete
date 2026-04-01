<?php

namespace Src\core;


class Container
{
    private static array $bindings = [];
    private static array $instances = [];


    public static function set(string $key, callable $resolver): void
    {
        self::$bindings[$key] = $resolver;
    }

    public static function get(string $key): mixed
    {

        if (isset(self::$instances[$key])) {
            return self::$instances[$key];
        }


        if (!isset(self::$bindings[$key])) {
            if (class_exists($key)) {
                return new $key();
            }
            throw new \Exception("Dependencia nao registrada: $key");
        }


        $instance = self::$bindings[$key]();
        self::$instances[$key] = $instance;

        return $instance;
    }


    public static function has(string $key): bool
    {
        return isset(self::$bindings[$key]) || isset(self::$instances[$key]);
    }


    public static function clear(): void
    {
        self::$bindings = [];
        self::$instances = [];
    }
}
