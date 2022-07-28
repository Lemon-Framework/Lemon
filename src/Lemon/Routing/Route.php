<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Support\Types\Str;

class Route
{
    public readonly MiddlewareCollection $middlewares;
    private string $patern = 'a-zA-Z_\-0-9';

    public function __construct(
        private readonly string $path,
        private array $actions,
    ) {
        $this->middlewares = new MiddlewareCollection();
    }

    /**
     * Adds/returns route action of given method.
     */
    public function action(string $method, callable $action = null): static|null|callable
    {
        $method = (string) Str::toLower($method);
        if (!$action) {
            return $this->actions[$method] ?? null;
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
            $this->middlewares->add($middleware);
        }

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

        return preg_match('~^[/]*'.$patern.'[/]*$~', $path, $matches)
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
}
