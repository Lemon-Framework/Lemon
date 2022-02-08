<?php

namespace Lemon\Http\Routing;

use Exception;
use Lemon\Http\Request;
use Lemon\Kernel\Lifecycle;
use Lemon\Support\Types\Arr;
use Lemon\Support\Types\Array_;

/**
 * The Lemon Router
 *
 * @method \Lemon\Http\Routing\Route get(string $path, $action) Creates route with method get
 * @method \Lemon\Http\Routing\Route post(string $path, $action) Creates route with method post
 * @method \Lemon\Http\Routing\Route put(string $path, $action) Creates route with method put
 * @method \Lemon\Http\Routing\Route head(string $path, $action) Creates route with method head
 * @method \Lemon\Http\Routing\Route delete(string $path, $action) Creates route with method delete
 * @method \Lemon\Http\Routing\Route path(string $path, $action) Creates route with method path
 * @method \Lemon\Http\Routing\Route options(string $path, $action) Creates route with method options
 *
 */
class Router
{
    /** @var \Lemon\Kernel\Lifecycle */
    private Lifecycle $lifecycle;

    public Array_ $routes;

    public const REQUEST_METHODS = [
            'get',
            'post',
            'put',
            'head',
            'delete',
            'path',
            'options'
    ];

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
        $this->routes = new Array_();
    }

    /**
     * Creates new route
     *
     * @param string $path
     * @param array<string> $methods
     * @param callable $action
     * @return \Lemon\Http\Routing\Route
     */
    public function crate(string $path, array $methods, callable $action)
    {
        $route = new Route($path, $methods, $action);
        $this->routes->push($route);
        return $route;
    }

    public function __call($name, $arguments)
    {
        if (!Arr::contains(self::REQUEST_METHODS, $name)) {
            throw new Exception('Call to undefined method Router::' . $name . '()');
        }

        return $this->crate($arguments[0], [$name], $arguments[1]);
    }

    /**
     * Creates new route with every request method
     *
     * @param string $path
     * @param callable $action
     * @return \Lemon\Http\Routing\Route
     */
    public function any(string $path, callable $action)
    {
        return $this->crate($path, self::REQUEST_METHODS, $action);
    }

    public function view(string $path, string $view = null)
    {
        // TODO
        return $this->create($path, $view);
    }

    /***
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
     * Finds route depending on given request
     *
     * @param \Lemon\Http\Request
     * @return \Lemon\Http\Response
     */
    public function dispatch(Request $request)
    {
        $dispatcher = new Dispatcher($this->routes->content, $request);
        return $dispatcher->dispatch();
    }
}
