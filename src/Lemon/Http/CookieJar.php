<?php

declare(strict_types=1);

namespace Lemon\Http;

use Lemon\Contracts\Http\CookieJar as CookieJarContract;

class CookieJar implements CookieJarContract
{
    private array $set_cookies = [];

    public function __construct(
        private Request $request,
    ) {
    }

    public function get(string $name): ?string
    {
        return $this->request->getCookie($name);
    }

    public function set(string $name, string $value, int $expires = 0, string $samesite = 'None'): static
    {
        $this->set_cookies[] = [[$name, $value], ['expires' => $expires, 'SameSite' => $samesite]];

        return $this;
    }

    public function delete(string $name): static
    {
        if (!$this->request->hasCookie($name)) {
            return $this;
        }
        $this->set_cookies[] = [$name, '', -1];

        return $this;
    }

    public function has(string $name): bool
    {
        return $this->request->hasCookie($name);
    }

    public function cookies(): array
    {
        return $this->set_cookies;
    }
}
