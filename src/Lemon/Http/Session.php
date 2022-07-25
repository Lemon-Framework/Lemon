<?php

declare(strict_types=1);

namespace Lemon\Http;

/** TODO change depending on tests TODO TESTS */
interface Session
{
    public function get(string $key): string;

    public function set(string $key, mixed $value): static;

    public function has(string $key): bool;

    public function expireAt(int $seconds): static;

    public function clear(): void;
}
