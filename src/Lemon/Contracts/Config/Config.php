<?php

declare(strict_types=1);

namespace Lemon\Contracts\Config;

interface Config
{
    /**
     * Loads config data from given directory.
     */
    public function load(string $directory = 'config'): static;

    /**
     * Returns value for given key in config.
     */
    public function get(string $key): mixed;

    /**
     * Returns project file for given key in config.
     */
    public function file(string $key, string $extension = null): string;

    /**
     * Sets key in config for given value.
     */
    public function set(string $key, mixed $value): static;
}
