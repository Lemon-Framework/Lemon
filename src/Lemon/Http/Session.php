<?php

declare(strict_types=1);

namespace Lemon\Http;

/** TODO change depending on tests TODO TESTS */
interface Session
{
    public function get(string $key): string;

    public function has(string $key): bool;
}
