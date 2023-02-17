<?php

declare(strict_types=1);

namespace Lemon\Contracts\Http;

interface CookieJar
{
    public function get(string $name): ?string;

    public function set(string $name, string $value, int $expires = 0): static;

    public function delete(string $name): static;

    public function has(string $name): bool;

    public function cookies(): array;
}
