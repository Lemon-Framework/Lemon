<?php

declare(strict_types=1);

namespace Lemon\Contracts\Support;

interface Env
{
    /**
     * Returns env value of given key or default if not present.
     */
    public function get(string $key, mixed $default = null): mixed;

    /**
     * Returns file with name from env.
     */
    public function file(string $key, string $extension, mixed $default = null): string;

    /**
     * Returns whenever env key exist.
     */
    public function has(string $key): bool;

    /**
     * Sets env key with given value.
     */
    public function set(string $key, string $value): void;

    /**
     * Returns env data.
     */
    public function data(): array;
}
