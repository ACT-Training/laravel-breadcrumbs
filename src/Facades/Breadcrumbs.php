<?php

namespace ActTraining\LaravelBreadcrumbs\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static void define(string $route, callable $callback)
 * @method static array generate(string $route, ...$parameters)
 * @method static array generateFromRoute()
 * @method static bool exists(string $route)
 * @method static void forget(string $route)
 * @method static void clear()
 * @method static array all()
 *
 * @see \ActTraining\LaravelBreadcrumbs\Breadcrumbs
 */
class Breadcrumbs extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \ActTraining\LaravelBreadcrumbs\Breadcrumbs::class;
    }
}