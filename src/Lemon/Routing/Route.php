<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Routing\Exceptions\RouteException;
use Lemon\Support\Types\Str;

class Route
{
    public readonly MiddlewareCollection $middlewares;

    private string $patern = 'a-zA-Z_\-0-9';

    private array $exclude = [];

    public function __construct(
        private readonly string $path,
        private array $actions,
    ) {
        $this->middlewares = new MiddlewareCollection();
    }

    /**
     * Adds/returns route action of given method.
     */
    public function action(string $method, callable|array $action = null): static|null|callable
    {
        $method = strtolower($method);
        if (!$action) {
            return isset($this->actions[$method])
                ? $this->handle($this->actions[$method])
                : null
            ;
        }
        $this->actions[$method] = $action;

        return $this;
    }

    /**
     * Adds middleware(s).
     */
    public function middleware(string|array ...$middlewares): static
    {
        foreach ($middlewares as $middleware) {
            if (!in_array($middleware, $this->exclude)) {
                $this->middlewares->add($middleware);
            }
        }

        return $this;
    }

    /**
     * Excludes middleware(s).
     */
    public function exclude(string|array ...$middlewares): static
    {
        $this->exclude = [...$this->exclude, ...$middlewares];

        return $this;
    }

    /**
     * Sets matching patern.
     */
    public function patern(string $patern): static
    {
        $this->patern = $patern;

        return $this;
    }

    /**
     * Returns array of matched dynamic data, null if not matching.
     */
    public function matches(string $path): ?array
    {
        $patern = $this->buildRegex();

        return preg_match('~^'.$patern.'$~', $path, $matches)
            ? array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY)
            : null
        ;
    }

    /**
     * Returns regex patern for route.
     */
    public function buildRegex(): string
    {
        return preg_replace('/\{([a-zA-Z_0-9]+)\}/', '(?<$1>['.$this->patern.']+)', $this->path);
    }

    private function handle(callable|array $action): callable
    {
        if (is_callable($action)) {
            return $action;
        }

        if (!class_exists($action[0])) {
            throw new RouteException('Class '.$action[0].' does not exist.');
        }

        $result = [new $action[0](), $action[1]];

        if (!is_callable($result)) {
            throw new RouteException('Action '.$action[0].'::'.$action[1].'()'.' is not callable.');
        }

        return $result;
    }
}
