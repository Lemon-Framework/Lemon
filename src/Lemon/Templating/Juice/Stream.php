<?php

declare(strict_types=1);

namespace Lemon\Templating\Juice;

final class Stream
{
    private int $position = 0;

    public function __construct(
        private array $tokens,
    ) {
    }
}
