<?php

declare(strict_types=1);

namespace Lemon\Routing;

class MiddlewareCollection
{
    private array $middlewares = [];

    /**
     * Resolves middleware name.
     */
    public function resolve(): array
    {
        return array_map(
            fn ($item) => is_array($item)
                ? [new $item[0](), $item[1]]
                : [new $item(), 'handle'],
            $this->middlewares
        );
    }

    /**
     * Adds middleware.
     */
    public function add(string|array $name): static
    {
        $this->middlewares[] = $name;

        return $this;
    }

    /**
     * Returns all middlewares.
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }
}
