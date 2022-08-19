<?php

declare(strict_types=1);

namespace Lemon\Contracts\Http;

interface Session
{
    /**
     * Sets expiration.
     */
    public function expireAt(int $seconds): static;

    /**
     * Returns value of given key.
     */
    public function get(string $key): string;

    /**
     * Sets value for given key.
     */
    public function set(string $key, mixed $value): static;

    /**
     * Determins whenever key exists.
     */
    public function has(string $key): bool;

    /**
     * Clears session.
     */
    public function clear(): void;
}
