<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Routing\Exceptions\RouteException;
use Lemon\Support\Types\Arr;

class Collection
{
    /**
     * List of collected routes.
     *
     * @var array<string, \Lemon\Routing\Route|static>
     */
    private array $routes = [];

    private string $prefix = '';

    private array $middlewares = [];

    private array $exclude = [];

    /**
     * Adds route to collection.
     */
    public function add(string $path, string $method, callable|array $action): Route
    {
        $path = trim($path, '/');
        if ($this->has($path)) {
            return $this->find($path)->action($method, $action);
        }

        $route = new Route($path, [$method => $action]);
        $this->routes[$path] = $route;

        return $route;
    }

    /**
     * Returns route with given path.
     */
    public function find(string $path): Route
    {
        $path = trim($path, '/');
        if (!$this->has($path)) {
            throw new RouteException('Route '.$path.' does not exist');
        }

        return $this->routes[$path];
    }

    /**
     * Determins whenever route exists.
     */
    public function has(string $path): bool
    {
        $path = trim($path, '/');

        return Arr::hasKey($this->routes, $path);
    }

    /**
     * Adds new collection into collection.
     */
    public function collection(self $collection): static
    {
        $this->routes[] = $collection;

        return $this;
    }

    /**
     * Adds collective middleware.
     */
    public function middleware(string|array ...$middlewares): static
    {
        $this->middlewares = [...$this->middlewares, ...$middlewares];

        return $this;
    }

    /**
     * Excludes colective middleware.
     */
    public function exclude(string|array ...$middlewares): static
    {
        $this->exclude = [...$this->exclude, ...$middlewares];

        return $this;
    }

    /**
     * Sets prefix.
     */
    public function prefix(string $prefix = null): string|static
    {
        if (!$prefix) {
            return $this->prefix;
        }

        $this->prefix = trim($prefix, '/');

        return $this;
    }

    /**
     * Finds route with mathing path.
     *
     * @return ?array{0: \Lemon\Routing\Route, 1: array<string, string>}
     */
    public function dispatch(string $path): ?array
    {
        if ($this->prefix) {
            if (preg_match("/^({$this->prefix})(.+)$/", $path, $matches)) {
                $path = $matches[2];
            } else {
                return null;
            }
        }
        foreach ($this->routes as $route) {
            if ($route instanceof Collection) {
                if (!is_null($found = $route->dispatch($path))) {
                    if ($this->exclude) {
                        $found[0]->exclude(...$this->exclude);
                    }

                    if ($this->middlewares) {
                        $found[0]->middleware(...$this->middlewares);
                    }

                    return $found;
                }
            }

            if ($route instanceof Route) {
                if (!is_null($found = $route->matches($path))) {
                    if ($this->exclude) {
                        $route->exclude(...$this->exclude);
                    }

                    if ($this->middlewares) {
                        $route->middleware(...$this->middlewares);
                    }

                    return [$route, $found];
                }
            }
        }

        return null;
    }

    public function routes(): array
    {
        return $this->routes;
    }
}
