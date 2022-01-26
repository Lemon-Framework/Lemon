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
 * @method \Lemon\Http\Routing\Route get(string $path, $action)
 * @method \Lemon\Http\Routing\Route post(string $path, $action)
 * @method \Lemon\Http\Routing\Route put(string $path, $action)
 * @method \Lemon\Http\Routing\Route head(string $path, $action)
 * @method \Lemon\Http\Routing\Route delete(string $path, $action)
 * @method \Lemon\Http\Routing\Route path(string $path, $action)
 * @method \Lemon\Http\Routing\Route options(string $path, $action)
 *
 */
class Router
{

    public readonly Lifecycle $lifecycle;

    public Array_ $routes;

    public readonly array $request_methods; 

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
        $this->routes = new Array_();

        $this->request_methods = [
            'get',
            'post',
            'put',
            'head',
            'delete',
            'path',
            'options'
        ];
    }

    public function crate(string $path, array $methods, callable $action)
    {
        $route = new Route($path, $methods, $action);
        $this->routes->push($route);  
        return $route;
    }

    public function __call($name, $arguments)
    {
        if (!Arr::contains($this->request_methods, $name))
            throw new Exception('Call to undefined method Router::' . $name . '()');

        return $this->crate($arguments[0], [$name], $arguments[1]);
    }

    public function any(string $path, callable $action)
    {
        return $this->crate($path, $this->request_methods, $action);
    }

    public function view(string $path, string $view = null)
    {
        // TODO 
        return $this->create($path, $view);
    }

    public function group(closure $routes)
    {/***
        $saved = $this->routes;
        $this->routes = [];
        $routes();
        $group = new RouteGroup($this->routes);
        $this->routes = $saved;
        $this->routes->push($group);
        return $group; 
     */
        // TODO
    }

    public function dispatch(Request $request)
    {
        $dispatcher = new Dispatcher($this->routes->content, $request);
        return $dispatcher->dispatch();
    }
}
