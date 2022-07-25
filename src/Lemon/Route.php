<?php

declare(strict_types=1);

namespace Lemon;

use Exception;

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
 * @method static Route any(string $path, callable $action) The Lemon Router.
 * @method static Response dispatch(Request $request) @author CoolFido sort of
 *
 * @see \Lemon\Routing\Router
 */
class Route extends Zest
{
    public static function unit(): string
    {
        return 'routing';
    }

    public static function dispatch()
    {
        // This basically prevents calling method dispatch in zest
        throw new Exception('Call to undefined method Route::dispatch');
    }
}
