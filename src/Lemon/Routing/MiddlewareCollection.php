<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Kernel\Container;
use Lemon\Routing\Exceptions\RouteException;

class MiddlewareCollection
{
    private array $middlewares;

    public function __construct(
        private Container $registered_middlewares,
    ) {
    }

    public function resolve(string|array $name): array
    {
        if (is_array($name)) {
            return $name;
        }

        return [$name, 'handle'];
    }

    public function add(string|array $name): static
    {
        $action = $this->resolve($name);
        if (!is_callable($action)) {
            throw new RouteException('Middleware '.implode('::', $action).' is not valid');
        }
        $this->middlewares[] = $action;

        return $this;
    }

    public function middlewares(): array
    {
        return $this->middlewares;
    }
}
