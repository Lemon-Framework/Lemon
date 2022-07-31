<?php

declare(strict_types=1);

namespace Lemon\Protection;

use Lemon\Support\Types\Str;

class Csrf
{
    private ?string $token = null;

    /**
     * Returns csrf token and creates new if does not exist.
     */
    public function getToken(): string
    {
        if (!$this->token) {
            $this->token = Str::random(32);
        }

        return $this->token;
    }

    public function created(): bool
    {
        return !is_null($this->token);
    }
}
