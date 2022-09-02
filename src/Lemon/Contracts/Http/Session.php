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
     * Removes expiration.
     */
    public function dontExpire(): static;

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
     * Removes key.
     */
    public function remove(string $key): static;

    /**
     * Clears session.
     */
    public function clear(): void;
}
