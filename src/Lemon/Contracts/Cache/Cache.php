<?php

declare(strict_types=1);

namespace Lemon\Contracts\Cache;

use Psr\SimpleCache\CacheInterface;

interface Cache extends CacheInterface
{
    /**
     * Returns cached data.
     */
    public function data(): array;

    /**
     * Returns cached value or executes given action.
     */
    public function retreive(string $key, callable $action): mixed;
}
