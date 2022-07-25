<?php

declare(strict_types=1);

namespace Lemon\Routing;

use Lemon\Kernel\Container;
use Lemon\Routing\Exceptions\RouteException;

class MiddlewareCollection
{
    public function __construct(
        private Container $middlewares,
    ) {
    }

    /**
     * Resolves middleware name
     */
    public function resolve(string|array $name): array
    {
        if (is_array($name)) {
            return $name;
        }

        return [$name, 'handle'];
    }

    /**
     * Adds middleware
     */
    public function add(string|array $name): static
    {
        $action = $this->resolve($name);
        if (!is_callable($action)) {
            throw new RouteException('Middleware '.implode('::', $action).' is not valid');
        }
        $this->middlewares[] = $action;

        return $this;
    }

    /**
     * Returns all middlewares
     */
    public function middlewares(): array
    {
        return $this->middlewares->services();
    }
}
