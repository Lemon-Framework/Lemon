<?php

declare(strict_types=1);

namespace Lemon\Testing;

use Lemon\Contracts\Http\Session;
use Lemon\Http\Exceptions\SessionException;

/**
 * Dont use it.
 *
 * @internal
 */
final class SessionMock implements Session
{
    public function __construct(
        private array $data
    ) {
    }

    public function get(string $key): array
    {
        if (!$this->has($key)) {
            throw new SessionException('Session key '.$key.' does not exist');
        }

        return $this->data[$key];
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function set(string $key, mixed $value): static
    {
        $this->data[$key] = $value;

        return $this;
    }

    public function expireAt(int $seconds): static
    {
        return $this;
    }

    public function dontExpire(): static
    {
        return $this;
    }

    public function remove(string $key): static
    {
        unset($this->data[$key]);

        return $this;
    }

    public function clear(): void
    {
        $this->data = [];
    }
}
