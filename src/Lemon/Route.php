<?php

declare(strict_types=1);

namespace Lemon;

/**
 * Lemon Router Zest
 * Provides static layer over the Lemon Router.
 *
 * @method static \Lemon\Http\Routing\Route get(string $path, $action)     Creates route with method get
 * @method static \Lemon\Http\Routing\Route post(string $path, $action)    Creates route with method post
 * @method static \Lemon\Http\Routing\Route put(string $path, $action)     Creates route with method put
 * @method static \Lemon\Http\Routing\Route head(string $path, $action)    Creates route with method head
 * @method static \Lemon\Http\Routing\Route delete(string $path, $action)  Creates route with method delete
 * @method static \Lemon\Http\Routing\Route path(string $path, $action)    Creates route with method path
 * @method static \Lemon\Http\Routing\Route options(string $path, $action) Creates route with method options
 *
 * @see \Lemon\Http\Routing\Router
 */
class Route extends Zest
{
    public static function unit()
    {
        return 'routing';
    }
}
