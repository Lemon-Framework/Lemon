<?php

namespace Lemon\Http\Routing;

use Lemon\Kernel\Lifecycle;
use Lemon\Support\Types\Array_;

class Router
{

    public readonly Lifecycle $lifecycle;

    public readonly Array_ $routes;

    public function __construct(Lifecycle $lifecycle)
    {
        $this->lifecycle = $lifecycle;
        $this->routes = new Array_();
    }

    public function crate(string $path, array $methods, callable $action)
    {
        $route = new Route($path, $methods, $action);
        $this->routes->push($route);   
    }
}
