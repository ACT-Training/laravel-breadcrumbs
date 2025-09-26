<?php

namespace ActTraining\LaravelBreadcrumbs;

class Breadcrumbs
{
    protected static array $breadcrumbs = [];

    public static function define(string $route, callable $callback): void
    {
        static::$breadcrumbs[$route] = $callback;
    }

    public static function generate(string $route, ...$parameters): array
    {
        if (! isset(static::$breadcrumbs[$route])) {
            return [];
        }

        $generator = new BreadcrumbGenerator;
        static::$breadcrumbs[$route]($generator, ...$parameters);

        return $generator->getBreadcrumbs();
    }

    public static function generateFromRoute(): array
    {
        $currentRoute = \Illuminate\Support\Facades\Route::current();

        if (! $currentRoute || ! $currentRoute->getName()) {
            return [];
        }

        return static::generate(
            $currentRoute->getName(),
            ...array_values($currentRoute->parameters())
        );
    }

    public static function exists(string $route): bool
    {
        return isset(static::$breadcrumbs[$route]);
    }

    public static function forget(string $route): void
    {
        unset(static::$breadcrumbs[$route]);
    }

    public static function clear(): void
    {
        static::$breadcrumbs = [];
    }

    public static function all(): array
    {
        return static::$breadcrumbs;
    }
}