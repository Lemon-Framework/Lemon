<?php

declare(strict_types=1);

namespace Lemon\Http\Routing;

use Exception;
use Lemon\Http\Request;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;

/**
 * The Lemon Router.
 *
 * @method \Lemon\Http\Routing\Route get(string $path, $action)     Creates route with method get
 * @method \Lemon\Http\Routing\Route post(string $path, $action)    Creates route with method post
 * @method \Lemon\Http\Routing\Route put(string $path, $action)     Creates route with method put
 * @method \Lemon\Http\Routing\Route head(string $path, $action)    Creates route with method head
 * @method \Lemon\Http\Routing\Route delete(string $path, $action)  Creates route with method delete
 * @method \Lemon\Http\Routing\Route path(string $path, $action)    Creates route with method path
 * @method \Lemon\Http\Routing\Route options(string $path, $action) Creates route with method options
 */
class Router
{
    public const REQUEST_METHODS = [
        'get',
        'post',
        'put',
        'head',
        'delete',
        'path',
        'options',
    ];

    public Array_ $routes;

    public function __construct()
    {
        $this->routes = new Array_();
    }

    public function __call($name, $arguments)
    {
        if (!Arr::contains(self::REQUEST_METHODS, $name)) {
            throw new Exception('Call to undefined method Router::'.$name.'()');
        }

        return $this->crate($arguments[0], [$name], $arguments[1]);
    }

    /**
     * Creates new route.
     *
     * @param array<string> $methods
     */
    public function crate(string $path, array $methods, callable $action): Route
    {
        $route = new Route($path, $methods, $action);
        $this->routes->push($route);

        return $route;
    }

    /**
     * Creates new route with every request method.
     */
    public function any(string $path, callable $action): Route
    {
        return $this->crate($path, self::REQUEST_METHODS, $action);
    }

    public function view(string $path, ?string $view = null)
    {
        // TODO
    }

    /*
    public function group($routes)
    {
        $saved = $this->routes;
        $this->routes = [];
        $routes();
        $group = new RouteGroup($this->routes);
        $this->routes = $saved;
        $this->routes->push($group);
        return $group;
        // TODO
    }

     */

    /**
     * Finds route depending on given request.
     */
    public function dispatch(Request $request): \Lemon\Http\Response
    {
        $dispatcher = new Dispatcher($this->routes->content, $request);

        return $dispatcher->dispatch();
    }
}
