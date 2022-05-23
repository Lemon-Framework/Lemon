<?php

declare(strict_types=1);

namespace Lemon\Http;

use Lemon\Http\Exceptions\CookieException;

class Cookies
{
    public function set(string $name, string $value, int $expires): static
    {
        setcookie(                
            $name,
            $value,
            $expires, // Todo datetime manipulation
            httponly: true
        );
        return $this;
    }

    public function has(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    public function get(string $name): mixed
    {
        if (!$this->has($name)) {
            throw new CookieException('Cookie '.$name.' does not exist');
        }
        return $_COOKIE[$name];
    }

    public function remove(string $name): static
    {
        unset($_COOKIE[$name]);
        return $this;
    }
}
