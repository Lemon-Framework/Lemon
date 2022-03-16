<?php

declare(strict_types=1);

namespace Lemon\Http\Routing;

/**
 * Class representing route group.
 *
 * @param array $parameters
 * @param array $routes
 */
class RouteGroup
{
    /**
     * Group name.
     */
    public $name;

    /**
     * Group route prefix.
     */
    public $prefix;

    /**
     * Group route middlewares.
     */
    public $middlewares;

    /**
     * Group member routes.
     */
    public $routes;

    public function __construct(array $parameters, array $routes)
    {
        $this->name = $parameters['name'] ?? '';
        $this->middlewares = $parameters['middlewares'] ?? [];
        $this->prefix = $parameters['prefix'] ?? '/';
        $this->routes = $routes;
        $this->resolve();
        $this->update();
    }

    /**
     * Resolves nested route groups and arrays of routes.
     */
    public function resolve(): void
    {
        foreach ($this->routes as $pos => $route) {
            if (is_array($route)) {
                $this->resolveRoute($pos, $route);
            } elseif ($route instanceof RouteGroup) {
                $this->resolveRoute($pos, $route->routes);
            }
        }
    }

    /**
     * Resolves routes that aren't Route instance.
     */
    public function resolveRoute(mixed $pos, mixed $routes): void
    {
        unset($this->routes[$pos]);
        $this->routes = array_merge($this->routes, $routes);
    }

    /**
     * Updates every group member to given parameters.
     */
    public function update(): void
    {
        foreach ($this->routes as $route) {
            if ($this->name !== '') {
                $route->name = $this->name.':'.$route->name;
            }
            $route->middleware($this->middlewares);
            $route->prefix($this->prefix);
        }
    }
}
