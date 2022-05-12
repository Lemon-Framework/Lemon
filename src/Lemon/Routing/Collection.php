<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Http\Request;
use Lemon\Routing\Exceptions\RouteException;
use Lemon\Support\Types\Arr;

class Collection
{
    /**
     * List of collected routes.
     *
     * @var array<string, Route|static>
     */
    private array $routes;

    /*
        TODO STUFF

        private MiddlewareCollection $middlewares;

        private string $prefix;

        and functions for it okacko

     */

    public function add(string $path, string $method, callable $action): Route
    {
        if ($this->has($path)) {
            return $this->find($path)->action($method, $action);
        }

        $route = new Route($path, [$method => $action]);
        $this->routes[$path] = $route;

        return $route;
    }

    public function find(string $path): Route
    {
        if (!$this->has($path)) {
            throw new RouteException('Route '.$path.' does not exist');
        }

        return $this->routes[$path];
    }

    public function has(string $path): bool
    {
        return Arr::hasKey($this->routes, $path);
    }

    public function collection(self $collection): static
    {
        $this->routes[] = $collection;

        return $this;
    }

    public function dispatch(Request $request): ?array
    {
        foreach ($this->routes as $route) {
            if ($route instanceof Collection) {
                if ($found = $route->dispatch($request)) {
                    return $found;
                }
            }

            if ($route instanceof Route) {
                if ($found = $route->matches($request)) {
                    return [$route, $found];
                }
            }
        }

        return null;
    }
}
