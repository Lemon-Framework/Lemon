<?php

declare(strict_types=1);

namespace Lemon\Contracts\Protection;

interface Csrf
{
    /**
     * Returns csrf token and creates new if does not exist.
     */
    public function getToken(): string;

    /**
     * Returns whenever token is created.
     */
    public function created(): bool;
}
