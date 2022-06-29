<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Support\Types\Str;

class Route
{
    public readonly string $path;

    private string $patern = 'a-zA-Z_\-0-9';

    public function __construct(
        string $path,
        private array $actions,
        public readonly MiddlewareCollection $middlewares
    ) {
        $this->path = trim($path, '/');
    }

    public function action(string $method, callable $action = null): static|null|callable
    {
        $method = (string) Str::toLower($method);
        if (!$action) {
            return $this->actions[$method] ?? null;
        }
        $this->actions[$method] = $action;

        return $this;
    }

    public function middleware(string|array ...$middlewares): static
    {
        foreach ($middlewares as $middleware) {
            $this->middlewares->add($middleware);
        }

        return $this;
    }

    public function patern(string $patern): static
    {
        $this->patern = $patern;

        return $this;
    }

    public function matches(string $path): ?array
    {
        $patern = $this->buildRegex();

        if ($patern == $this->path) {
            return $path == $this->path ? [] : null;
        }

        return preg_match('~^'.$patern.'$~', $path, $matches) 
            ? array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY) 
            : null
        ;
    }

    public function buildRegex(): string
    {
        return preg_replace('/\{([a-zA-Z_0-9]+)\}/', '(?<$1>['.$this->patern.']+)', $this->path);
    }
}
