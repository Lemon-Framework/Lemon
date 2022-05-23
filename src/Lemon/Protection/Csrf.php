<?php

declare(strict_types=1);

namespace Lemon\Protection;

use Lemon\Http\Cookies;
use Lemon\Support\Types\Str;

class Csrf
{
    public function __construct(
        private Cookies $cookies
    ) {
        
    }

    public function getToken(): string
    {
        if (!$this->cookies->has('CSRF_TOKEN')) {
            $this->cookies->set('CSRF_TOKEN', Str::random(32), 0);
        }

        return $this->cookies->get('CSRF_TOKEN');
    } 

    public function reset(): void
    {
        $this->cookies->remove('CSRF_TOKEN');
    }

    public function validate(string $token): bool
    {
        return $token == $this->getToken();
    }
}
