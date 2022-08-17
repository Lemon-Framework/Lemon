<?php

declare(strict_types=1);

namespace Lemon;

use Exception;

/**
 * Lemon Router Zest
 * Provides static layer over the Lemon Router.
 *
 * @method static \Lemon\Routing\Route get(string $path, $action)                        Creates route with method get
 * @method static \Lemon\Routing\Route post(string $path, $action)                       Creates route with method post
 * @method static \Lemon\Routing\Route put(string $path, $action)                        Creates route with method put
 * @method static \Lemon\Routing\Route head(string $path, $action)                       Creates route with method head
 * @method static \Lemon\Routing\Route delete(string $path, $action)                     Creates route with method delete
 * @method static \Lemon\Routing\Route path(string $path, $action)                       Creates route with method path
 * @method static \Lemon\Routing\Route options(string $path, $action)                    Creates route with method options
 * @method static \Lemon\Routing\Route any(string $path, callable $action)               The Lemon Router.
 * @method static \Lemon\Routing\Route template(string $path, ?string $view = null)      Creates GET route directly returning view
 * @method static \Lemon\Routing\Collection collection(callable $routes)                 Creates collection of routes created in given callback
 * @method static \Lemon\Routing\Collection file(string $file)                           Creates collection of routes created in given file
 * @method static \Lemon\Routing\Collection controller(string $base, string $controller) Creates collection of given controller
 * @method static \Lemon\Http\Response dispatch(Request $request)                        Finds route depending on given request.
 * @method static \Lemon\Routing\Collection routes()                                     Returns all routes
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
