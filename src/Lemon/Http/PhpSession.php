<?php

declare(strict_types=1);

namespace Lemon\Http;

use Lemon\Config\Config;
use Lemon\Http\Exceptions\SessionException;

/** TODO some high level stuff, maybe rewrite to be tests-friendly */
class PhpSession implements Session
{
    private bool $started = false;

    public function __construct(
        private Config $config,
    ) {
    }

    public function __destruct()
    {
        if (!$this->started) {
            return;
        }

        session_gc();
        session_commit();
    }

    public function init(): void
    {
        if ($this->started) {
            return;
        }

        $this->started = true;

        session_start([
            'name' => $this->config->part('http')->get('session.name'),
            'cookie_lifetime' => 3600,
        ]);
    }

    public function expireAt(int $seconds): static
    {
        $this->init();

        session_set_cookie_params($seconds);

        return $this;
    }

    public function dontExpire(): static
    {
        return $this->expireAt(0);
    }

    public function get(string $key): string
    {
        $this->init();
        if (!$this->has($key)) {
            throw new SessionException('Session key '.$key.' does not exist');
        }

        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, string $value): static
    {
        $this->init();
        $_SESSION[$key] = $value;

        return $this;
    }

    public function has(string $key): bool
    {
        $this->init();

        return isset($_SESSION[$key]);
    }

    public function remove(string $key): static
    {
        $this->init();
        unset($_SESSION[$key]);

        return $this;
    }

    public function clear()
    {
        $this->init();
        session_destroy();
    }
}
