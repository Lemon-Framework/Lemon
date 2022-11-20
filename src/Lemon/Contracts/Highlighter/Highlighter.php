<?php

declare(strict_types=1);

namespace Lemon\Contracts\Highlighter;

interface Highlighter
{
    public function highlight(string $code): string;
}
